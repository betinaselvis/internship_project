$(function(){
  $('#loginForm').on('submit', function(e){
    e.preventDefault();
    var email = $('#email').val().trim();
    var password = $('#password').val();
    $.ajax({
      url: 'api/login.php',
      method: 'POST',
      data: {email: email, password: password},
      dataType: 'json'
    }).done(function(resp){
      if(resp.success){
        // Save token and user id to localStorage
        localStorage.setItem('auth_token', resp.token);
        localStorage.setItem('user_id', resp.user_id);
        window.location.href = 'profile.html';
      } else {
        $('#loginResult').html('<div class="alert alert-danger">'+(resp.message||'Login failed')+'</div>');
      }
    }).fail(function(){
      $('#loginResult').html('<div class="alert alert-danger">Network error</div>');
    });
  });
});