<?php

namespace App\Http\Resources\Support;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class MessageResource extends JsonResource
{
    public static $wrap = null;

    public function __construct(
        public ?string $message = null
    ) {
        $this->message = $message ?? __('ui/crud.success.retrieved', ['resource' => 'Data']);
        parent::__construct($this);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, string>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
