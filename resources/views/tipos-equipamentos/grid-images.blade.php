@if(count($arrayImages) > 0)
    <div class="row mb-3">

        <div class="d-flex align-items-center justify-content-between">
            <label for="checkAllImages">
                <input id="checkAllImages" type="checkbox" class="form-check-input me-2">
                Selecionar todas imagens
            </label>
            <button type="button" class="addLaudoImages btn btn-sm btn-primary d-none">Adicionar</button>
        </div>
    </div>
    <div class="row form-row">
        @foreach($arrayImages as $index => $image)
            <div class="col-md-6">
                <label for="check{{ $index }}" style="cursor: pointer; position: relative">
                    <input id="check{{ $index }}" type="checkbox" class="form-check-input checkSelectLaudoImages m-0" data-index="{{ $index }}">
                    <img
                    id="image{{ $index }}"
                    src="{{$image}}"
                    alt="laudo figure"
                    class="item-draggable pictures-laudo"
                    style="padding:2%;"
                    >
                    <a href="javascript:void(0)" id="{{$image}}" class="removeImg" style="color:red; font-size: 20px">
                        <i class="bx bxs-trash"></i>
                    </a>
                </label>

            </div>
        @endforeach
    </div>
@else
    {{-- <a href="javascript:void(0)" class="addPacoteFiguras">  Adicione uma ou mais figuras. </a> --}}
    <p>Nenhuma imagem adicionada.</p>
@endif
