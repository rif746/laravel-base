<?php

use App\Domains\Identity\Actions\IdentityMaintenance\UpdateUserAvatar;
use App\Domains\Identity\Models\User;
use App\Domains\System\Actions\Files\ReplaceSingleFile;
use App\Domains\System\DTOs\FileDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can update user avatar', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('avatar.jpg');

    $mockReplaceSingleFile = $this->mock(ReplaceSingleFile::class, function (MockInterface $mock) use ($user, $file) {
        $mock->shouldReceive('execute')
            ->once()
            ->with($file, Mockery::on(function (FileDTO $dto) use ($user) {
                return $dto->modelType === $user->getMorphClass() &&
                       $dto->modelId === $user->id &&
                       $dto->relationName === 'avatar' &&
                       $dto->disk === 'local' &&
                       $dto->directory === 'avatars';
            }));
    });

    $action = new UpdateUserAvatar($mockReplaceSingleFile);
    $action->execute($user, $file);
});
