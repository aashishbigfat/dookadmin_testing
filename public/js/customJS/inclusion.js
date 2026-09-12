const elIcon = document.querySelector('.inclusionSelect_Icon');
var elSelect = document.querySelector('.inclusionSelectIcon');
var ico_set=1;
function selectIcon(e,obj,ico){
   // alert(ico);

    ico_set=ico;
    elIcon.classList.add('show');
}
const removeIcon = (e) => {
    let parentE = e.parentNode;
    let r_icon = parentE.querySelectorAll('.selectedIcon');
    for(let i =0; i < r_icon.length; i++){
        r_icon[i].classList.remove('active');
        console.log('work');
    }
}
function selectedIcon(e){
    //alert(ico_set);
    let parentDiv = e.parentNode;
    removeIcon(parentDiv);
    parentDiv.classList.add('active');
    parentDiv.querySelector('input').checked = true;
    let select_icon = parentDiv.querySelector('img');

    let iconName = parentDiv.querySelector('input').value;
    //alert(iconName);
    document.getElementById('clickIcon_'+ico_set).innerHTML = `<img src='${select_icon.src}'><span><input type='hidden' name='icon[]' value='${iconName}'></span>`;
    elIcon.classList.remove('show');
}
window.onclick = function(event) {
    if (!event.target.matches('.inclusionSelectIcon')) {
        let hideBox = document.querySelector('.inclusionSelect_Icon');
        if(hideBox){
            
        if(hideBox.classList.contains('show')){
            hideBox.classList.remove('show');
        }
        }
    }
}