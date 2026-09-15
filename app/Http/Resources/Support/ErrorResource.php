<?php

namespace App\Http\Resources\Support;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class ErrorResource extends JsonResource
{
    public static $wrap = null;

    public function __construct(
        public ?string $message
    ) {
        parent::__construct($this);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => __($this->message),
        ];
    }
}
