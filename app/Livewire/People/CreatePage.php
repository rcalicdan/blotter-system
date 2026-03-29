<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use App\Services\PhotoUploadService;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Add Person')]
class CreatePage extends Component
{
    use WithFileUploads;

    public string $first_name = '';
    public string $last_name = '';
    public string $birthdate = '';
    public string $address = '';
    public string $contact_number = '';
    public $photo = null;

    public function mount(): void
    {
        $this->authorize('create', Person::class);
    }

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'contact_number' => ['nullable', 'string', 'regex:/^(\+639|09)\d{9}$/'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(PhotoUploadService $uploader): void
    {
        $validated = $this->validate();

        if ($this->photo) {
            $validated['photo'] = $uploader->store($this->photo);
        }

        Person::create($validated);

        RedirectNotification::success("Person \"{$this->first_name} {$this->last_name}\" has been added.");

        $this->redirect(route('people.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.people.create-page');
    }
}
