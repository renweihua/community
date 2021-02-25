<?php

namespace App\Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    protected $instance;
    protected $validate_id = '';

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        if ( method_exists($this, 'setInstance') ) {
            $this->setInstance();
        }

        $this->setValidateId();
    }

    protected function setValidateId()
    {
        if ($this->instance){
            $primarykey = $this->instance->getKeyName();
            $this->validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey . ($this->instance->getIsDelete() == 0 ? ',is_delete,0' : '');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
