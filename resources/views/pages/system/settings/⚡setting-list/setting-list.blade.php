@use(App\UI\Enums\InputType)
<div class="row justify-content-center">
    <div class="row gx-4 row-gap-4">
        @foreach ($this->settings as $group)
            <div class="col-sm-12 col-md-6">
                <div class="row row-gap-4 gx-4">
                    @foreach($group as $title => $section)
                        <div class="col-sm-12">
                            <x-card :title="$title">
                                @foreach($section as $field)
                                    <x-form.vertical-group :label="$field->label()">
                                        <x-slot:action>
                                            <button class="btn btn-sm btn-info" data-bs-target="#update-setting-modal"
                                                    data-bs-toggle="modal" data-id="{{ $field->value }}">
                                                @svg('tabler-edit', ['width' => 16, 'height' => 16])
                                            </button>
                                        </x-slot:action>
                                        @if($field->schema()->type->isFile())
                                            <img class="card-img mt-1 border"
                                                 src="{{ $this->settingsValue[$field->value] }}" alt="">
                                        @else
                                            {{ $this->settingsValue[$field->value] ?? '-' }}
                                        @endif
                                    </x-form.vertical-group>
                                @endforeach
                            </x-card>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <livewire:pages::system.settings.update-setting-modal/>
</div>
