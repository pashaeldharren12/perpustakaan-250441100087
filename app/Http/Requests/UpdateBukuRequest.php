<?php

namespace App\Http\Requests;

/**
 * Mewarisi StoreBukuRequest (authorize, messages, attributes ikut diwarisi).
 *
 * Satu-satunya perbedaan: pada update, field boleh dikirim sebagian
 * (mis. hanya "judul"). Maka rule "required" milik Store diganti menjadi
 * "sometimes" -> bila field dikirim, tetap divalidasi penuh dengan rule yang sama.
 */
class UpdateBukuRequest extends StoreBukuRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $rules = [];

        foreach (parent::rules() as $field => $fieldRules) {
            $rules[$field] = array_merge(
                ['sometimes'],
                array_values(array_filter($fieldRules, fn ($rule) => $rule !== 'required'))
            );
        }

        return $rules;
    }
}
