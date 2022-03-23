<div class="row">
    <div class="col-md-12">
        <div class="card"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-6">
                                <h5> Filtro <i class="fa fa-filter"> </i> </h5>
                            </div>
                            <div class="col-6">
                                <button 
                                    class="btn btn-outline-primary float-end"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#filterCollapse"
                                    aria-expanded="false"
                                    aria-controls="filterCollapse"
                                > 
                                    <i class="fas fa-angle-double-down"></i> 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse" id="filterCollapse"> 
                    <hr/>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>