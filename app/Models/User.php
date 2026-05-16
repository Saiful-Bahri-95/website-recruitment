<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

/**
 * @method \Illuminate\Database\Eloquent\Relations\HasOne biodata()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany documents()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany educations()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany workExperiences()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany emergencyContacts()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany payments()
 * @method \Illuminate\Database\Eloquent\Relations\HasOne application()
 */

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ===== Filament Access Control =====
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    // ===== Relationships =====
    public function biodata(): HasOne
    {
        return $this->hasOne(Biodata::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class)->orderBy('urutan');
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function application(): HasOne
    {
        return $this->hasOne(Application::class);
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class);
    }

    /**
     * Hitung progres aplikasi pelamar — dipakai dashboard & halaman status.
     */
    public function getProgressData(): array
    {
        $application = $this->application;
        $status = $application?->status ?? 'draft';

        $biodataDone = $this->biodata()->exists();

        $documentsCount = $this->documents()->count();
        $documentsDone = $documentsCount >= 10;

        $paymentDone = in_array($status, ['paid', 'verified']);
        $verificationDone = $status === 'verified';

        $steps = [
            'biodata'    => $biodataDone,
            'dokumen'    => $documentsDone,
            'pembayaran' => $paymentDone,
            'verifikasi' => $verificationDone,
        ];

        $completed = count(array_filter($steps));

        return [
            'steps'           => $steps,
            'completed'       => $completed,
            'total'           => 4,
            'percentage'      => (int) round(($completed / 4) * 100),
            'documents_count' => $documentsCount,
            'status'          => $status,
        ];
    }
}
