<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use App\Services\PhotoUploadService;
use App\Services\RedirectNotification;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Edit Person')]
class UpdatePage extends Component
{
    use WithFileUploads;

    public Person $person;

    public string $first_name = '';
    public string $last_name = '';
    public string $birthdate = '';
    public string $address = '';
    public string $contact_number = '';
    public $photo = null;

    public function mount(Person $person): void
    {
        $this->authorize('update', $person);

        $this->person = $person;
        $this->first_name = $person->first_name;
        $this->last_name = $person->last_name;
        $this->birthdate = $person->birthdate?->format('Y-m-d') ?? '';
        $this->address = $person->address ?? '';
        $this->contact_number = $person->contact_number ?? '';
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
            $validated['photo'] = $uploader->replace($this->photo, $this->person->photo);
        } else {
            unset($validated['photo']);
        }

        $this->person->update($validated);

        RedirectNotification::success("Person \"{$this->person->full_name}\" has been updated.");

        $this->redirect(route('people.index'), navigate: true);
    }

    public function removePhoto(PhotoUploadService $uploader): void
    {
        $uploader->delete($this->person->photo);

        $this->person->update(['photo' => null]);
    }

    public function render()
    {
        return view('livewire.people.update-page');
    }
}
