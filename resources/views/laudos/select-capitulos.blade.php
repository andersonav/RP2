<div class="col-md-12">
    @if(count($dataModeloLaudo->laudoCapitulos) > 0)
        @foreach($dataModeloLaudo->laudoCapitulos as $key => $laudoCapitulos)
            <div class="caps">
                <div class="form-check mb-2">
                    <input
                        class="form-check-input inputcaps"
                        type="checkbox"
                        name="capitulos[{{$key}}][id_capitulo]"
                        value="{{$laudoCapitulos->id}}"
                        checked
                        key={{$key}}
                        />
                    <label class="form-check-label"> <b>{{$laudoCapitulos->nome_capitulo}} </b> </label>
                </div>

                @if(count($laudoCapitulos->laudoModeloSubcapitulos) > 0)
                    @foreach($laudoCapitulos->laudoModeloSubcapitulos as $k => $laudoSubcapitulos)
                        <div class="subcaps {{ !$laudoSubcapitulos->nome_subcapitulo ? 'd-none' : '' }}">
                            <div class="form-check ms-4">
                                <input
                                class="form-check-input inputsubcaps"
                                type="checkbox"
                                name="capitulos[{{$key}}][subcapitulos][{{$k}}][id_subcap]"
                                value="{{$laudoSubcapitulos->id}}"
                                subcap={{$key}}
                                key="{{$k}}"
                                checked
                                />
                                <label class="form-check-label"> {{ $laudoSubcapitulos->nome_subcapitulo}} </label>
                            </div>

                            @if(count($laudoSubcapitulos->subCapsN3) > 0)
                                @foreach($laudoSubcapitulos->subCapsN3 as $i => $n3)
                                    <div class="n3">
                                        <div class="form-check ms-5">
                                            <input
                                            class="form-check-input inputn3"
                                            type="checkbox"
                                            name="capitulos[{{$key}}][subcapitulos][{{$k}}][n3][{{$i}}][id_n3]"
                                            value="{{$n3->id}}"
                                            n3="{{$key}}_{{$k}}"
                                            key="{{$i}}"
                                            checked
                                            />
                                            <label class="form-check-label"> {{$n3->nome_sub_subcapitulo}} </label>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    @else
        <p class="text-danger"> Sem capítulos para este laudo  </p>
    @endif
</div>
