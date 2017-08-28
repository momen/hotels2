@extends('app') 
@section('content')
  <h2>Information Details</h2> 
  <div style="float: right; padding-bottom: 10px; ">
    <input type="text" name="searchkey" id="searchkey" ><input type="button" name="search" value="Search" onclick="searchkeyword()" >
  </div> 
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name <a  href="javascript:changestatus('name','1')" id="nn" class="namecls" ><img src="images/sort_neutral.png" height="16" width="16" border="0" ></a><a href="javascript:changestatus('name','2')" id="na" class="namecls" style="display:none;" ><img src="images/sort_down.png" height="16" width="16" border="0" ></a><a href="javascript:changestatus('name','1')" id="nd" class="namecls" style="display:none;" ><img src="images/sort_up.png" height="16" width="16" border="0" ></a></th>
        <th>Price <a  href="javascript:changestatus('price','1')" id="nnp" class="pricecls" ><img src="images/sort_neutral.png" height="16" width="16" border="0" ></a><a href="javascript:changestatus('price','2')" id="nap" class="pricecls" style="display:none;" ><img src="images/sort_down.png" height="16" width="16" border="0" ></a><a href="javascript:changestatus('price','1')" id="ndp" class="pricecls" style="display:none;" ><img src="images/sort_up.png" height="16" width="16" border="0" ></a></th>
        <th>City  </th>
        <th>Availability</th> 
      </tr>
    </thead>
    <tbody> 
      @include('hotel.table')   
    </tbody>
  </table> 
@section('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
  <script type="text/javascript" >  
        function changestatus(mode, status)
        {  
            $.ajax({    
              type:'GET',
              url:base_url+'changeListing/'+mode+'/'+status,
              success:function(data){   
                  if(mode == "name")
                  {  
                    $(".pricecls").hide(); $("#nnp").show();
                    if(status==1) {  $("#nn").hide(); $("#nd").hide();  $("#na").show();  }
                    if(status==2) {  $("#na").hide(); $("#nn").hide();  $("#nd").show(); } 
                  }  
                  if(mode == "price")
                  {  
                    $(".namecls").hide(); $("#nn").show();
                    if(status==1) {  $("#nnp").hide(); $("#ndp").hide();  $("#nap").show();  }
                    if(status==2) {  $("#nap").hide(); $("#nnp").hide();  $("#ndp").show();  }  
                  }                        
                 $('tbody').html(data.view);
              },
              error: function(XMLHttpRequest, textStatus, errorThrown){
                $(".err").html("<div>Error.</div>");
              }
              }); 
        }

        function searchkeyword()
        {  
            var getsearch = $("#searchkey").val();
            var trimval = getsearch.trim(); 
            if(trimval != '' ) { var searchwrd = trimval; } else { var searchwrd = "1"; }
            $.ajax({    
              type:'GET',
              url:base_url+'searchbykey/'+searchwrd,
              success:function(data){  
                $(".namecls").hide(); $("#nn").show();
                $(".pricecls").hide(); $("#nnp").show(); 
                $('tbody').html(data.view);    
              },
              error: function(XMLHttpRequest, textStatus, errorThrown){
                $(".err").html("<div>Error.</div>");
              }
              }); 
        }
</script>
@stop 
@endsection