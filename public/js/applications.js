  document.addEventListener("DOMContentLoaded", () =>{
    const list = document.getElementById("applicationList");
    const numApplications = document.getElementById("numApplications");

    if(list && numApplications){
      const count = list.querySelectorAll("jobapp").length;
      numApplications.textContent = `Number of Applications: ${count}`;
    }

  });