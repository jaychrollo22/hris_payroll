<div class="modal fade" id="rateData" tabindex="-1" role="dialog" aria-labelledby="rateDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rateDataLabel">Rate</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  onsubmit='get_salary();show();return false;'  >
          <div class="modal-body">
            <div class='row' id='password_data'>
                <div class='col-md-12'>
                    Password (<i><small>Please enter your password to view rate</small></i>)
                    <input type="password" id='password_salary' name="password_salary" class='form-control form-control-sm' placeholder="Password" required/>
                </div>
            </div>
            <div id='result' class='row '>
              
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form> 
      </div>
    </div>
</div>
<script>
      var element = document.getElementById("password_data");
    function get_salary()
    {
        var pass = $("input[name=password_salary]").val();
        reset_data();
            $.ajax({
                dataType: 'json',
                type:'GET',
                url:  'get-salary',
                data:{password_salary:pass},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(data){
                console.log(data);
                document.getElementById("loader").style.display="none";
                if(data.password_salary == pass)
                {
                    $('#result').append('<div class="col-md-12 text-center"><span class="text-danger">Error Password</span>  </div>')
                }
                else
                {
                  
                    element.classList.add("invisible");
                    $('#result').append('<div class="col-md-6 border">Monthly Rate : '+Number(data.employee.payment_info.monthly_rate).toLocaleString('en')+'</div><div class="col-md-6 border">Daily Rate : '+Number(parseFloat(data.employee.payment_info.daily_rate).toLocaleString('en'))+'</div>');
                }
               
            });
    }
    function reset_data()
    {
        $('#result').empty();
        $('#password_salary').val("");
        var element = document.getElementById("password_data");
        element.classList.remove("invisible");
    }
</script>