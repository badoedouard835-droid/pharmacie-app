<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlerteVendeur extends Notification implements ShouldQueue
{
    use Queueable;

    protected $titre;
    protected $message;
    protected $type;

    /**
     * Créer une nouvelle notification
     */
    public function __construct($titre, $message, $type = 'info')
    {
        $this->titre = $titre;
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Canaux de notification
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Message email
     */
    public function toMail(object $notifiable): MailMessage
    {
        $typeLabel = match($this->type) {
            'info' => '📋 Information',
            'success' => '✅ Succès',
            'warning' => '⚠️ Avertissement',
            'danger' => '🚨 Urgent',
            default => 'Notification'
        };

        return (new MailMessage)
            ->greeting("Bonjour {$notifiable->name},")
            ->subject("[$typeLabel] {$this->titre}")
            ->line($this->message)
            ->action('Consulter', url('/dashboard'))
            ->line('Merci d\'avoir lu ce message.')
            ->salutation('Cordialement, l\'équipe administrateur');
    }

    /**
     * Données pour la base de données
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'titre' => $this->titre,
            'message' => $this->message,
            'type' => $this->type,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name,
        ];
    }
}