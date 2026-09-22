$(document).ready(function () {
  $(".select2").select2();
  $(document).on('click', '.goBack', () => {
    window.history.back();
  });

})

function toggleText(button) {
  var textContainer = button.previousElementSibling; 
  textContainer.classList.toggle('expanded'); 

  if (textContainer.classList.contains('expanded')) {
    button.textContent = 'See Less'; 
  } else {
    button.textContent = 'See More'; 
  }
}

window.addEventListener('load', function () {
  var textContainers = document.querySelectorAll('.am-text-container');
  textContainers.forEach(function (textContainer) {
    var button = textContainer.nextElementSibling; 
    if (textContainer.scrollHeight > textContainer.clientHeight) {
      button.classList.add('show'); 
    }
  });
});


