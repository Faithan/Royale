function showHistoryDashboard() {

    var middleContent0 = document.getElementById("middleContent0");
    var middleContent1 = document.getElementById("middleContent1");
    var middleContent2 = document.getElementById("middleContent2");
  
    middleContent0.style.display = "contents";
    middleContent1.style.display = "none";
    middleContent2.style.display = "none";


    var navList1 = document.getElementById("navList1");
    var navList2 = document.getElementById("navList2");


    navList1.style.backgroundColor = '#182B4D';
    navList1.style.color = '#E5ECFA';
    navList2.style.backgroundColor = '#182B4D';
    navList2.style.color = '#E5ECFA';

}

function showWalkInPaymentHistory() {

    var middleContent0 = document.getElementById("middleContent0");
    var middleContent1 = document.getElementById("middleContent1");
    var middleContent2 = document.getElementById("middleContent2");
  
    middleContent0.style.display = "none";
    middleContent1.style.display = "contents";
    middleContent2.style.display = "none";


    var navList1 = document.getElementById("navList1");
    var navList2 = document.getElementById("navList2");


    navList1.style.backgroundColor = '#E5ECFA';
    navList1.style.color = '#182B4D';
    navList2.style.backgroundColor = '#182B4D';
    navList2.style.color = '#E5ECFA';

}

function showOnlinePaymentHistory() {

    var middleContent0 = document.getElementById("middleContent0");
    var middleContent1 = document.getElementById("middleContent1");
    var middleContent2 = document.getElementById("middleContent2");
  
    middleContent0.style.display = "none";
    middleContent1.style.display = "none";
    middleContent2.style.display = "contents";




    var navList1 = document.getElementById("navList1");
    var navList2 = document.getElementById("navList2");

    navList1.style.backgroundColor = '#182B4D';
    navList1.style.color = '#E5ECFA';
    navList2.style.backgroundColor = '#E5ECFA';
    navList2.style.color = '#182B4D';
}

// for zooming imags
function zoomImage(image) {
    image.classList.toggle('zoomed');
}
