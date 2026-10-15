<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Viaje;
use Database\Seeders\TrayectiaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrayectiaTest extends TestCase
{
    use RefreshDatabase;

    private function jorge(): User
    {
        $this->seed(TrayectiaSeeder::class);

        return User::where('email', 'donjorge@transportes.mx')->first();
    }

    public function test_las_vistas_publicas_cargan(): void
    {
        $this->get('/login')->assertOk()->assertSee('Bienvenido de nuevo');
        $this->get('/registro')->assertOk()->assertSee('Únete a la red');
        $this->get('/salida')->assertOk()->assertSee('Redirigiendo al Acceso');
        $this->get('/panel')->assertRedirect('/login');
    }

    public function test_login_por_whatsapp_y_error_de_contrasena(): void
    {
        $this->jorge();

        $this->post('/login', ['usuario' => '999 123 4567', 'password' => 'mala'])->assertSessionHasErrors('password');
        $this->post('/login', ['usuario' => '999 123 4567', 'password' => 'trayectia2026'])->assertRedirect('/panel');
        $this->assertAuthenticated();
    }

    public function test_registro_crea_cuenta_e_inicia_sesion(): void
    {
        $this->post('/registro', [
            'name' => 'Ana Pech', 'email' => 'ana@fletes.mx', 'whatsapp' => '999 555 0000',
            'password' => 'Camion2026!', 'password_confirmation' => 'Camion2026!',
            'empresa' => 'Fletes Ana', 'ciudad' => 'Umán, Yucatán', 'unidad_principal' => 'Rabón',
        ])->assertRedirect('/panel');

        $this->assertDatabaseHas('users', ['email' => 'ana@fletes.mx', 'whatsapp' => '9995550000']);
        $this->get('/panel')->assertOk()->assertSee('Don Ana')->assertSee('No hay viajes activos');
    }

    public function test_todas_las_vistas_privadas_cargan(): void
    {
        $jorge = $this->jorge();
        $viaje = $jorge->viajes()->where('folio', 15)->first();

        $this->actingAs($jorge);
        $this->get('/panel')->assertOk()->assertSee('Viajes activos en ruta')->assertSee('#015');
        $this->get('/panel?unidad=rabon')->assertOk()->assertSee('#014');
        $this->get('/viajes/nuevo')->assertOk()->assertSee('Registrar nuevo viaje');
        $this->get('/viajes')->assertOk();
        $this->get("/viajes/{$viaje->id}")->assertOk()->assertSee('Línea de Tiempo Operativa');
        $this->get('/aprobaciones')->assertOk()->assertSee('#014');
        $this->get('/cobros')->assertOk()->assertSee('Bandeja de Liquidación');
        $this->get('/cobros?ver=atrasados')->assertOk()->assertSee('Atraso crítico');
        $this->get('/cerrar-sesion')->assertOk()->assertSee('¿Cerrar sesión');
    }

    public function test_cotizador_recalcula_en_servidor(): void
    {
        $jorge = $this->jorge();

        $this->actingAs($jorge)->post('/viajes', [
            'cliente' => 'Constructora del Sureste', 'origen' => 'Mérida', 'destino' => 'Caucel',
            'fecha' => today()->toDateString(), 'hora' => '07:30', 'espera_hrs' => 1.5,
            'unidad' => 'torton', 'chofer' => 'luis', 'distancia_km' => 38, 'precio' => 4500, 'casetas' => 0,
        ])->assertRedirect();

        $viaje = $jorge->viajes()->where('folio', 16)->firstOrFail();
        // Valores del diseño de Stitch: 38 km / 3.2 km/L × $25.50 = $302.81
        $this->assertEquals(302.81, $viaje->costo_diesel);
        $this->assertEquals(650.0, $viaje->viatico);
        $this->assertEquals('en_ruta', $viaje->estado);
    }

    public function test_flujo_completo_entrega_aprobacion_factura_cobro(): void
    {
        $jorge = $this->jorge();
        $viaje = $jorge->viajes()->where('folio', 15)->first();

        $this->actingAs($jorge)->post("/viajes/{$viaje->id}/avanzar")->assertSessionHas('exito');
        $this->assertEquals('entregado', $viaje->fresh()->estado);

        // El cliente aprueba desde el link público, sin sesión.
        auth()->logout();
        $this->get("/aprobar/{$viaje->token_aprobacion}")->assertOk()->assertSee('Aprobar recepción completa');
        $this->post("/aprobar/{$viaje->token_aprobacion}", ['aprobado_por' => 'Arq. Manuel E.'])->assertSessionHas('exito');
        $this->assertEquals('aprobado', $viaje->fresh()->estado);

        $this->actingAs($jorge)->post("/viajes/{$viaje->id}/avanzar");
        $this->assertEquals('facturado', $viaje->fresh()->estado);
        $this->post("/viajes/{$viaje->id}/avanzar");
        $this->assertEquals('cobrado', $viaje->fresh()->estado);
    }

    public function test_no_se_puede_ver_viaje_de_otro_usuario(): void
    {
        $viaje = $this->jorge()->viajes()->first();

        $this->actingAs(User::factory()->create())->get("/viajes/{$viaje->id}")->assertNotFound();
    }

    public function test_editar_perfil_y_foto(): void
    {
        Storage::fake('public');
        $jorge = $this->jorge();

        $this->actingAs($jorge)->get('/perfil')->assertOk()->assertSee('Editar perfil')->assertSee('donjorge@transportes.mx');

        $this->put('/perfil', [
            'name' => 'Jorge Peniche Canul', 'email' => 'jorge@nuevo.mx', 'whatsapp' => '(999) 765-4321',
            'empresa' => 'Fletes Peniche', 'ciudad' => 'Progreso, Yucatán', 'unidad_principal' => 'Rabón',
            'avatar' => UploadedFile::fake()->create('yo.jpg', 120, 'image/jpeg'),
        ])->assertSessionHas('exito');

        $jorge->refresh();
        $this->assertSame('jorge@nuevo.mx', $jorge->email);
        $this->assertSame('9997654321', $jorge->whatsapp);
        Storage::disk('public')->assertExists($jorge->avatar);

        // El nuevo correo sirve para iniciar sesión.
        auth()->logout();
        $this->post('/login', ['usuario' => 'jorge@nuevo.mx', 'password' => 'trayectia2026'])->assertRedirect('/panel');
    }

    public function test_perfil_no_permite_correo_de_otro_usuario(): void
    {
        $jorge = $this->jorge();
        $otro = User::factory()->create(['email' => 'otro@fletes.mx']);

        $this->actingAs($jorge)->put('/perfil', [
            'name' => 'Jorge', 'email' => $otro->email, 'whatsapp' => '9991234567',
            'empresa' => 'X', 'ciudad' => 'Mérida', 'unidad_principal' => 'Torton',
        ])->assertSessionHasErrors('email');
    }

    public function test_cambiar_contrasena(): void
    {
        $jorge = $this->jorge();
        $this->actingAs($jorge);

        $this->put('/perfil/password', ['password_actual' => 'incorrecta', 'password' => 'NuevaClave2026', 'password_confirmation' => 'NuevaClave2026'])
            ->assertSessionHasErrorsIn('password', 'password_actual');

        $this->put('/perfil/password', ['password_actual' => 'trayectia2026', 'password' => 'NuevaClave2026', 'password_confirmation' => 'NuevaClave2026'])
            ->assertSessionHas('exito');

        auth()->logout();
        $this->post('/login', ['usuario' => '9991234567', 'password' => 'NuevaClave2026'])->assertRedirect('/panel');
    }

    public function test_cerrar_sesion(): void
    {
        $this->actingAs($this->jorge())->post('/logout')->assertRedirect('/salida');
        $this->assertGuest();
    }
}
