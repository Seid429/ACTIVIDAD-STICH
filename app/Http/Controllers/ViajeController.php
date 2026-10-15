<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ViajeController extends Controller
{
    public function index(Request $request): View
    {
        $estado = $request->query('estado');

        $viajes = $request->user()->viajes()
            ->when(array_key_exists($estado, Viaje::ESTADOS), fn ($q) => $q->where('estado', $estado))
            ->orderByDesc('folio')
            ->get();

        return view('trayectia.viajes.index', compact('viajes', 'estado'));
    }

    public function create(Request $request): View
    {
        return view('trayectia.viajes.nuevo', [
            'siguienteFolio' => ($request->user()->viajes()->max('folio') ?? 0) + 1,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'cliente' => ['required', 'string', 'max:120'],
            'origen' => ['required', 'string', 'max:120'],
            'destino' => ['required', 'string', 'max:120'],
            'carga' => ['nullable', 'string', 'max:160'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'espera_hrs' => ['required', 'numeric', 'min:0', 'max:48'],
            'unidad' => ['required', Rule::in(array_keys(config('trayectia.unidades')))],
            'chofer' => ['required', Rule::in(array_keys(config('trayectia.choferes')))],
            'distancia_km' => ['required', 'numeric', 'min:1', 'max:2000'],
            'precio' => ['required', 'numeric', 'min:1'],
            'casetas' => ['nullable', 'numeric', 'min:0'],
        ], [], [
            'cliente' => 'empresa cliente',
            'distancia_km' => 'distancia',
            'precio' => 'precio ofrecido',
            'espera_hrs' => 'espera en obra',
        ]);

        // Los costos se recalculan en el servidor; no se confía en lo que muestra el navegador.
        $costos = Viaje::cotizar($datos['unidad'], $datos['chofer'], $datos['distancia_km'], $datos['precio'], (float) ($datos['casetas'] ?? 0));

        $viaje = $request->user()->viajes()->create([
            ...$datos,
            'folio' => ($request->user()->viajes()->max('folio') ?? 0) + 1,
            'costo_diesel' => $costos['costo_diesel'],
            'casetas' => $costos['casetas'],
            'viatico' => $costos['viatico'],
            'desgaste' => $costos['desgaste'],
            'estado' => 'en_ruta',
            'avance' => 0,
        ]);

        return redirect()->route('viajes.show', $viaje)
            ->with('exito', "Viaje {$viaje->folio_texto} asignado a {$viaje->chofer_datos['nombre']}.");
    }

    public function show(Request $request, Viaje $viaje): View
    {
        $this->autorizar($request, $viaje);

        return view('trayectia.viajes.expediente', compact('viaje'));
    }

    /** Mueve el viaje al siguiente estado operativo. */
    public function avanzar(Request $request, Viaje $viaje): RedirectResponse
    {
        $this->autorizar($request, $viaje);

        $mensaje = match ($viaje->estado) {
            'en_ruta' => $this->marcarEntregado($viaje),
            'aprobado' => $this->facturar($viaje),
            'facturado' => $this->cobrar($viaje),
            default => null,
        };

        return back()->with($mensaje ? 'exito' : 'error', $mensaje ?? 'Este viaje no tiene un siguiente paso disponible.');
    }

    private function marcarEntregado(Viaje $viaje): string
    {
        $viaje->update(['estado' => 'entregado', 'avance' => 100, 'entregado_at' => now()]);

        return "Viaje {$viaje->folio_texto} marcado como entregado. Comparte el link para que el cliente lo firme.";
    }

    private function facturar(Viaje $viaje): string
    {
        $viaje->update([
            'estado' => 'facturado',
            'facturado_at' => now(),
            'fecha_pago' => today()->addDays(config('trayectia.dias_credito')),
        ]);

        return "CFDI del viaje {$viaje->folio_texto} generado. Pago pactado a ".config('trayectia.dias_credito').' días.';
    }

    private function cobrar(Viaje $viaje): string
    {
        $viaje->update(['estado' => 'cobrado', 'cobrado_at' => now()]);

        return "Cobro del viaje {$viaje->folio_texto} registrado: $".number_format($viaje->precio).' MXN.';
    }

    private function autorizar(Request $request, Viaje $viaje): void
    {
        abort_unless($viaje->user_id === $request->user()->id, 404);
    }
}
