// アカウント一覧のモーダル展開
// これは正しく動かない。クリックの中に、クリックを指定しているため、2回クリックしないと動作しなかった。
// let deleteAccount = (btnId)=> {
//   const btnIda = btnId;
//   const btnIndex = btnIda.indexOf('n');
//   const btnNumber = btnIda.slice(btnIndex + 1);
//   console.log(btnNumber)
  
//   const btn = document.getElementById(btnId);
//   const modal = document.getElementById('modal');

//   const deleteId = document.getElementById('deleteId');
//   deleteId.value = btnNumber;

//   btn.addEventListener('click', function(){
//     modal.style.display = 'block';
//   })
// };

let deleteAccount = (btnId)=> {
  const btnIda = btnId;
  const btnIndex = btnIda.indexOf('n');
  const btnNumber = btnIda.slice(btnIndex + 1);
  console.log(btnNumber)
  
  const btn = document.getElementById(btnId);
  const modal = document.getElementById('modal');

  const deleteId = document.getElementById('deleteId');
  deleteId.value = btnNumber;

  modal.style.display = 'block';
};

{
  const closeModal = document.getElementById('closeModal');
  if(closeModal){
    const modal = document.getElementById('modal');
    closeModal.addEventListener('click',()=>{
      modal.style.display = 'none';
    })
  }
}
// 上記の別パターン
// let closeM = () => {
//   const modal = document.getElementById('modal');
//     modal.style.display = 'none';
// };

// function closeModal() {
//   const closeBtn = document.getElementById('closeModal');
//   const modal = document.getElementById('modal');
//     closeBtn.addEventListener('click', function() {
//       modal.style.display = 'none';
//     })
// };
