<?php

namespace App\Livewire;

use App\Events\MessageSend;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;

class ManageMessages extends Component
{
    public $content;
    public $mensajes = [];

    public function mount()
    {
        $this->getMensajes();
    }

    #[On('echo:chat,MessageSend')]
    public function getMensajes()
    {
        $this->mensajes = Message::with('user')->latest()->take(50)->get()->reverse()->values();
    }

    public function save()
    {
        $this->validate([
            'content' => 'required|min:5',
        ]);

        $message = auth()->user()->messages()->create([
            'content' => $this->content,
        ]);

        $this->reset('content');

        MessageSend::dispatch($message);
        
        $this->getMensajes();
    }

    public function render()
    {
        return view('livewire.manage-messages');
    }
}
