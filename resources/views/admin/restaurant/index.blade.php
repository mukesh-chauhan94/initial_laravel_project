@extends('layouts.admin')
@section('content')
<div class="content">
        @can('restaurant_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" data-toggle="modal" data-target="#myModal" id="add_restaurant">
                    {{ trans('global.add') }} {{ trans('global.restaurants.title_singular') }}
                </a>

            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Restaurant List
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        Restaurant Name
                                    </th>
                                    <th>
                                        Email
                                    </th>
                                    <th>
                                        Code
                                    </th>
                                    <th>
                                        Phone Number
                                    </th>
                                    <th>
                                        Image
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restaurantData as $key => $restaurant)
                                    <tr data-entry-id="{{ $restaurant['id'] }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $restaurant['name'] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $restaurant['email'] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $restaurant['code'] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $restaurant['phone_number'] ?? '' }}
                                           
                                        </td>
                                        <td>
                                            @if(!empty($restaurant['restaurant_image']))
                                                <img src="{{ asset('image')}}/{{$restaurant['restaurant_image']['image_name']}}" height="50" width="50">
                                            @endif
                                           
                                        </td>
                                        <td>
                                            
                                            @can('restaurant_view')
                                                <a class="btn btn-xs btn-primary" href="javascript:;" onclick="showDetail('{{$restaurant['id']}}')">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan
                                            @can('restaurant_edit')
                                            
                                                <a class="btn btn-xs btn-info" href="javascript:;" onclick="editRestaurent('{{$restaurant['id']}}')">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan
                                            
                                            @can('restaurant_delete')
                                                <form action="{{ route('admin.restaurants.Destroy',['id'=>$restaurant['id']]) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan
                                            
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id ="model_header">Add Restaurent</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form  name="restaurantForm" id="restaurantForm"  enctype="multipart/form-data"> 
                            <input type="hidden" name="id" id="id">
                            <div class="form-group">
                                <label>Name<sup class="text-danger">*</sup></label>
                                <input type="text" id="name" name="name" class="form-control">
                                <span id="name_error" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label>Code<sup class="text-danger">*</sup></label>
                                <input type="text" id="code" name="code" class="form-control">
                                <span id="code_error" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label>Email<sup class="text-danger">*</sup></label>
                                <input type="text" id="email" name="email" class="form-control">
                                <span id="email_error" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label>Description<sup class="text-danger">*</sup></label>
                                
                                <textarea id="description" name="description" class="form-control" rows="5"></textarea>
                                <span id="description_error" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label>Phone Number<sup class="text-danger">*</sup></label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control">
                                <span id="phone_number_error" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label>Image<sup class="text-danger">*</sup></label>
                                <input type="file" id="image" name="image" class="form-control">
                                <span id="image_error" class="text-danger"></span>
                            </div>

                        </form>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="submitForm" class="btn btn-default" >Save</button>
              <button type="button" id="submitFormclose" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
</div>
<div class="modal fade" id="myModalShow" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" >Restaurent Detail</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">                              
                                    <label>Name</label>
                                </div>
                                <div class="col-md-8">
                                    <label id="show_name"></label>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Code</label>
                                </div>
                                
                                <div class="col-md-8">
                                    <label id="show_code"></label>                                    
                                </div>
                                </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-8">
                                    <label id="show_email"></label>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Description</label>
                                </div>
                                <div class="col-md-8">
                                    <label id="show_description"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Phone Number</label>
                                </div>
                                <div class="col-md-8">
                                    <label id="show_phone_number"></label>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Image</label>
                                </div>
                                <div class="col-md-8">
                                    <img id="show_image" height="50" width="50p">
                                </div>
                            </div>

                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="submitFormclose" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
</div>
@endsection
@section('scripts')
@parent
<script type="text/javascript" src="{{ asset('js/jquery.form.min.js')}}"></script>
<script>
    $(function () {
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.restaurants.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  dtButtons.push(deleteButton)


  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

    var token = "{{ csrf_token() }}";
    $('#add_restaurant').on('click', function(){
        $('#model_header').text('Add Restaurant')
        $("[id$='_error']").empty();
        $('#restaurantForm')[0].reset(); 
    })
    $('#submitForm').click('on',function(){
        $("[id$='_error']").empty();
        var id = $('#id').val();
        var submitUrl = "{{ route('admin.restaurants.store') }}";
        if(id){
            var submitUrl = "{{ route('admin.restaurants.update') }}";
        } 
        $('#restaurantForm').ajaxSubmit({
            url: submitUrl,
            type: 'POST',
            data: { "_token" : token},
            dataType: 'json',
            beforeSubmit : function()
            {
                $("div.overlay").css('display','block');
                $("[id$='_error']").empty();
                $('#submitForm').attr('disabled',true);
                $('#submitForm').text('Please wait...');
            },
            success : function(resp)
            {   
                $('#restaurantForm')[0].reset(); 
                window.location.reload(); 
            },
            error : function(respObj){
                $('#submitForm').attr('disabled',false);
                $('#submitForm').text('Save');
                if(id){
                    $('#submitForm').text('Update');
                }
                $.each(respObj.responseJSON.errors, function(k,v){
                    $('#'+k+'_error').text(v[0]);
                });
            }
        });
    })

    function showDetail(id){
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.restaurants.show') }}",
            data: { "_token" : token,'id':id },
            dataType: 'JSON',
            success: function(resp){
                $('#myModalShow').modal('show');
                var data = resp.RestaurantDetail;
                var restaurant_image = resp.RestaurantImage;
                $.each(data, function(k,v){                        
                    $('#show_'+k).text(v)
                })
                console.log()
                var image = "{{ asset('image') }}"+'/'+restaurant_image[0].image_name;
                $("#show_image").attr("src",image);
            }
        });  
    }

    function editRestaurent(id){
        $('#submitForm').text('Update');
        $('#model_header').text('Edit Restaurant');
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.restaurants.edit') }}",
            data: { "_token" : token,'id':id },
            dataType: 'JSON',
            success: function(resp){
                $('#myModal').modal('show');
                var data = resp.RestaurantDetail;
                $.each(data, function(k,v){                        
                    $('#'+k).val(v)
                })
            }
        });  
    }

</script>
@endsection