<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Viaje extends Model
{
    protected $table = 'viajes';

    protected $fillable = [
        'user_id', 'folio', 'cliente', 'origen', 'destino', 'carga', 'fecha', 'hora', 'espera_hrs',
        'unidad', 'chofer', 'distancia_km', 'precio', 'costo_diesel', 'casetas', 'viatico', 'desgaste',
        'estado', 'avance', 'token_aprobacion', 'entregado_at', 'aprobado_at', 'aprobado_por',
        'facturado_at', 'fecha_pago', 'cobrado_at', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'fecha_pago' => 'date',
            'entregado_at' => 'datetime',
            'aprobado_at' => 'datetime',
            'facturado_at' => 'datetime',
            'cobrado_at' => 'datetime',
            'distancia_km' => 'float',
            'espera_hrs' => 'float',
            'precio' => 'float',
            'costo_diesel' => 'float',
            'casetas' => 'float',
            'viatico' => 'float',
            'desgaste' => 'float',
        ];
    }

    public const ESTADOS = [
        'en_ruta' => ['etiqueta' => 'En ruta', 'clase' => 'bg-bad/10 text-bad'],
        'entregado' => ['etiqueta' => 'Entregado · Esperando firma', 'clase' => 'bg-mint text-pino'],
        'aprobado' => ['etiqueta' => 'Aprobado · Listo para facturar', 'clase' => 'bg-mint text-pino'],
        'facturado' => ['etiqueta' => 'Facturado · Por cobrar', 'clase' => 'bg-warn/20 text-[#7a5a12]'],
        'cobrado' => ['etiqueta' => 'Cobrado', 'clase' => 'bg-ok/15 text-ok'],
    ];

    protected static function booted(): void
    {
        static::creating(function (Viaje $viaje) {
            $viaje->token_aprobacion ??= Str::random(40);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Desglose de costos y rentabilidad de un viaje. Lo usan el formulario (vía JSON
     * en la vista) y el controlador al guardar, para que ambos calculen igual.
     */
    public static function cotizar(string $unidad, string $chofer, float $km, float $precio, float $casetas = 0): array
    {
        $u = config("trayectia.unidades.$unidad");
        $c = config("trayectia.choferes.$chofer");
        $diesel = round($km / $u['rendimiento'] * config('trayectia.precio_diesel'), 2);
        $viatico = (float) $c['tarifa'];
        $desgaste = round($km * config('trayectia.desgaste_km'), 2);
        $total = round($diesel + $casetas + $viatico + $desgaste, 2);
        $ganancia = round($precio - $total, 2);

        return [
            'costo_diesel' => $diesel,
            'casetas' => round($casetas, 2),
            'viatico' => $viatico,
            'desgaste' => $desgaste,
            'costo_total' => $total,
            'ganancia' => $ganancia,
            'margen' => $precio > 0 ? round($ganancia / $precio * 100) : 0,
        ];
    }

    public function scopeDelMes(Builder $query): Builder
    {
        return $query->whereBetween('fecha', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->whereIn('estado', ['en_ruta', 'entregado', 'aprobado']);
    }

    public function scopePorCobrar(Builder $query): Builder
    {
        return $query->where('estado', 'facturado');
    }

    public function scopeAtrasados(Builder $query): Builder
    {
        return $query->where('estado', 'facturado')->whereDate('fecha_pago', '<', today());
    }

    public function getCostoTotalAttribute(): float
    {
        return $this->costo_diesel + $this->casetas + $this->viatico + $this->desgaste;
    }

    public function getGananciaAttribute(): float
    {
        return $this->precio - $this->costo_total;
    }

    public function getMargenAttribute(): int
    {
        return $this->precio > 0 ? (int) round($this->ganancia / $this->precio * 100) : 0;
    }

    public function getFolioTextoAttribute(): string
    {
        return '#'.str_pad((string) $this->folio, 3, '0', STR_PAD_LEFT);
    }

    public function getUnidadDatosAttribute(): array
    {
        return config("trayectia.unidades.{$this->unidad}", ['nombre' => $this->unidad, 'capacidad' => '', 'rendimiento' => 0, 'placas' => '']);
    }

    public function getChoferDatosAttribute(): array
    {
        return config("trayectia.choferes.{$this->chofer}", ['nombre' => $this->chofer, 'iniciales' => '', 'tarifa' => 0, 'tipo' => '']);
    }

    public function getEstadoDatosAttribute(): array
    {
        return self::ESTADOS[$this->estado] ?? ['etiqueta' => $this->estado, 'clase' => 'bg-crema-2 text-muted'];
    }

    public function getDiasParaPagoAttribute(): ?int
    {
        return $this->fecha_pago ? (int) today()->diffInDays($this->fecha_pago, false) : null;
    }
}
