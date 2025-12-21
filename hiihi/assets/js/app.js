document.addEventListener('DOMContentLoaded',function(){
  var fbtn = document.getElementById('apply-filter-btn');
  if(fbtn) fbtn.addEventListener('click', function(){
    var min = parseFloat(document.getElementById('price-min').value) || 0;
    var maxV = document.getElementById('price-max').value;
    var max = maxV ? parseFloat(maxV) : null;
    document.querySelectorAll('.listing').forEach(function(el){
      var p = parseFloat(el.dataset.price) || 0;
      el.style.display = (p>=min && (max===null||p<=max)) ? 'flex':'none';
    });
  });
  var loginOpen = document.getElementById('login-open');
  if(loginOpen) loginOpen.addEventListener('click', function(e){ e.preventDefault(); document.getElementById('login-modal').style.display='flex'; });
  document.querySelectorAll('.modal-close').forEach(function(b){ b.addEventListener('click', function(){ this.closest('.modal').style.display='none'; })});
});