<?php

declare(strict_types=1);

namespace App\Livewire\Customer;

use App\Enums\PaymentMethod;
use App\Traits\ResolvesCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.customer')]
#[Title('Payment Methods')]
class PaymentMethods extends Component
{
    use ResolvesCustomer;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $type = '';

    public string $label = '';

    public string $provider = '';

    public string $accountNumber = '';

    public string $accountName = '';

    public bool $isDefault = false;

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_column($this->allowedTypes(), 'value'))],
            'label' => ['required', 'string', 'max:100'],
            'provider' => ['nullable', 'string', 'max:50'],
            'accountNumber' => ['required', 'string', 'max:50'],
            'accountName' => ['nullable', 'string', 'max:100'],
            'isDefault' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Please select a payment type.',
            'label.required' => 'Please provide a label for this payment method.',
            'accountNumber.required' => 'Please enter the account/phone number.',
        ];
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $customer = $this->resolveCustomer();
        $method = $customer->paymentMethods()->findOrFail($id);

        $this->editingId = $method->id;
        $this->type = $method->type->value;
        $this->label = $method->label;
        $this->provider = $method->provider ?? '';
        $this->accountNumber = $method->account_number;
        $this->accountName = $method->account_name ?? '';
        $this->isDefault = $method->is_default;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $customer = $this->resolveCustomer();

        if ($this->isDefault) {
            $customer->paymentMethods()->update(['is_default' => false]);
        }

        $data = [
            'type' => $this->type,
            'label' => $this->label,
            'provider' => $this->provider ?: null,
            'account_number' => $this->accountNumber,
            'account_name' => $this->accountName ?: null,
            'is_default' => $this->isDefault,
        ];

        if ($this->editingId) {
            $customer->paymentMethods()->where('id', $this->editingId)->update($data);
            $message = 'Payment method updated.';
        } else {
            if ($customer->paymentMethods()->count() === 0) {
                $data['is_default'] = true;
            }
            $customer->paymentMethods()->create($data);
            $message = 'Payment method added.';
        }

        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: $message);
    }

    public function setDefault(int $id): void
    {
        $customer = $this->resolveCustomer();
        $customer->paymentMethods()->update(['is_default' => false]);
        $customer->paymentMethods()->where('id', $id)->update(['is_default' => true]);

        $this->dispatch('toast', type: 'success', message: 'Default payment method updated.');
    }

    public function delete(int $id): void
    {
        $customer = $this->resolveCustomer();
        $method = $customer->paymentMethods()->findOrFail($id);
        $wasDefault = $method->is_default;
        $method->delete();

        if ($wasDefault) {
            $customer->paymentMethods()->oldest()->first()?->update(['is_default' => true]);
        }

        $this->dispatch('toast', type: 'success', message: 'Payment method removed.');
    }

    public function cancel(): void
    {
        $this->showForm = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function render(): View
    {
        $customer = $this->resolveCustomer();

        return view('livewire.customer.payment-methods', [
            'methods' => $customer?->paymentMethods()->latest()->get() ?? collect(),
            'allowedTypes' => $this->allowedTypes(),
        ]);
    }

    /** @return list<PaymentMethod> */
    private function allowedTypes(): array
    {
        return [
            PaymentMethod::MobileMoney,
            PaymentMethod::Card,
            PaymentMethod::BankTransfer,
        ];
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->type = '';
        $this->label = '';
        $this->provider = '';
        $this->accountNumber = '';
        $this->accountName = '';
        $this->isDefault = false;
    }
}
