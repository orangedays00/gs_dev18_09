// アカウント一覧のモーダル展開
function deleteAccount(btnId) {
  const btnIda = btnId;
  const btnIndex = btnIda.indexOf('n');
  const btnNumber = btnIda.slice(btnIndex + 1);
  
  const btn = document.getElementById(btnId);
  const modal = document.getElementById('modal');

  btn.addEventListener('click', function(){
    modal.style.display = 'block';
  })

  return btnNumber;
}

function closeModal() {
  const closeBtn = document.getElementById('closeBtn');
    closeBtn.addEventListener('click', function() {
      modal.style.display = 'none';
    })
}
