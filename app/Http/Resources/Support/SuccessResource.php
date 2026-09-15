<?php

namespace App\Http\Resources\Support;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Generic API Success Response Resource for OpenAPI & Scramble documentation.
 *
 * @template T
 */
class SuccessResource extends JsonResource
{
    /**
     * Create a new success resource instance.
     *
     * @param T $resource Data payload (Model, DTO, Array, or nested JsonResource)
     * @param string|null $message Custom success message returned to client
     */
    public function __construct(
        mixed $resource,
        protected ?string $message = null
    ) {
        parent::__construct($resource);

        // Fallback message if no custom string is provided
        $this->message = $message ?? __('ui/crud.success.retrieved', ['resource' => 'Data']);
    }

    /**
     * Transform the resource payload into a standardized API array structure.
     *
     * @param Request $request
     * @return array{message: string, data: T}
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message,
            /** @var T */
            'data' => $this->resource,
        ];
    }
}
