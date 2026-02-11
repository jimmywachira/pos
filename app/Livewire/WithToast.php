<?php

namespace App\Livewire;

trait WithToast
{
    public function showSuccessToast($message)
    {
        $this->dispatch('showSuccessToast', message: $message);
    }
}
