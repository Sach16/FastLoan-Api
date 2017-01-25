<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
class UserSettingsTransformer extends TransformerAbstract
{

    /**
     * User settings transformer
     *
     * @param array $settings
     * @return array
     */
    public function transform(array $settings)
    {
        return [
            'resident_status' => isset($settings['resident_status']) ? $settings['resident_status'] : null,
            'profession'      => isset($settings['profession']) ? $settings['profession'] : null,
            'dob'             => isset($settings['dob']) ? $settings['dob'] : null,
            'age'             => isset($settings['dob']) ? Carbon::parse($settings['dob'])->diffInYears(Carbon::now()) : 0,
            'education'       => isset($settings['education']) ? $settings['education'] : null,
            'marital_status'  => isset($settings['marital_status']) ? $settings['marital_status'] : null,
            'company'         => isset($settings['company']) ? $settings['company'] : null,
            'DOJ'             => isset($settings['DOJ']) ? $settings['DOJ'] : null,
            'exp_on_DOJ'      => isset($settings['exp_on_DOJ']) ? $settings['exp_on_DOJ'] : null,
            'net_income'      => isset($settings['net_income']) ? sprintf('%0.2f',$settings['net_income']) : null,
            'pan'             => isset($settings['pan']) ? $settings['pan'] : null,
            'salary_bank'     => isset($settings['salary_bank']) ? $settings['salary_bank'] : null,
            'skype'           => isset($settings['skype']) ? $settings['skype'] : null,
            'facetime'        => isset($settings['facetime']) ? $settings['facetime'] : null,
            'contact_time'    => isset($settings['contact_time']) ? $settings['contact_time'] : null,
            'cibil_score'     => isset($settings['cibil_score']) ? $settings['cibil_score'] : null,
            'cibil_status'    => isset($settings['cibil_status']) ? $settings['cibil_status'] : null,
        ];
    }
}