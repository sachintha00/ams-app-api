<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAssestRequisitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post')) {
            return [
                'requisitio n_id' => 'required|string|max:255',
                'requisition_date' => 'required|date',
                'items' => 'required|array',
                'items.*.itemName' => 'required|string|max:255',
                'items.*.assesttype' => 'required|string|max:255',
                'items.*.quantity' => 'required|integer',
                'items.*.budget' => 'required',
                'items.*.businessperpose' => 'required|string|max:255',
                'items.*.upgradeOrNew' => 'required|string|max:255',
                'items.*.periodStatus' => 'required|string|max:255',
                'items.*.periodFrom' => 'required|date',
                'items.*.periodTo' => 'required|date',
                'items.*.period' => 'required|string|max:255',
                'items.*.availabiityType' => 'required|string|max:255',
                'items.*.priority' => 'required|string|max:255',
                'items.*.requiredDate' => 'required|date',
                'items.*.reason' => 'required|string|max:255',
                'items.*.businessImpact' => 'required|string|max:255',
                'items.*.suppliers' => 'sometimes|array',
                'items.*.suppliers.*' => 'required|string|max:255',
                'items.*.files' => 'sometimes|array',
                'items.*.files.*' => 'sometimes|file|max:10240', // Max 10MB
                'items.*.itemDetails' => 'required|array',
                'items.*.itemDetails.*.type' => 'required|string|max:255',
                'items.*.itemDetails.*.details' => 'required|string|max:255',
                'items.*.maintenanceKpi' => 'required|array',
                'items.*.maintenanceKpi.*.details' => 'required|string|max:255',
                'items.*.serviceSupportKpi' => 'required|array',
                'items.*.serviceSupportKpi.*.details' => 'required|string|max:255',
                'items.*.consumablesKPI' => 'required|array',
                'items.*.consumablesKPI.*.details' => 'required|string|max:255'
            ];
        } else {
            return [
                'requisitio n_id' => 'required|string|max:255',
                'requisition_date' => 'required|date',
                'items' => 'required|array',
                'items.*.itemName' => 'required|string|max:255',
                'items.*.assesttype' => 'required|string|max:255',
                'items.*.quantity' => 'required|integer',
                'items.*.budget' => 'required',
                'items.*.businessperpose' => 'required|string|max:255',
                'items.*.upgradeOrNew' => 'required|string|max:255',
                'items.*.periodStatus' => 'required|string|max:255',
                'items.*.periodFrom' => 'required|date',
                'items.*.periodTo' => 'required|date',
                'items.*.period' => 'required|string|max:255',
                'items.*.availabiityType' => 'required|string|max:255',
                'items.*.priority' => 'required|string|max:255',
                'items.*.requiredDate' => 'required|date',
                'items.*.reason' => 'required|string|max:255',
                'items.*.businessImpact' => 'required|string|max:255',
                'items.*.suppliers' => 'sometimes|array',
                'items.*.suppliers.*' => 'required|string|max:255',
                'items.*.files' => 'sometimes|array',
                'items.*.files.*' => 'sometimes|file|max:10240', // Max 10MB
                'items.*.itemDetails' => 'required|array',
                'items.*.itemDetails.*.type' => 'required|string|max:255',
                'items.*.itemDetails.*.details' => 'required|string|max:255',
                'items.*.maintenanceKpi' => 'required|array',
                'items.*.maintenanceKpi.*.details' => 'required|string|max:255',
                'items.*.serviceSupportKpi' => 'required|array',
                'items.*.serviceSupportKpi.*.details' => 'required|string|max:255',
                'items.*.consumablesKPI' => 'required|array',
                'items.*.consumablesKPI.*.details' => 'required|string|max:255'
            ];
        }
    }
}
