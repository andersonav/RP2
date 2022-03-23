
<style>
    .page-break{ page-break-after: always } 
    html{
        border:1px;
        border-color: black;
    }
</style>

@php 
    $data = str_replace('../../../storage', '../../storage',$data);
@endphp  
{!! $data !!}
<div class="page-break"> </div>
