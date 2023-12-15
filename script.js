
function areObjectsDifferent(obj1, obj2) {
  // Controlla se la lunghezza degli array è diversa
  if (obj1.length !== obj2.length) {
    return true;
  }

  // Itera attraverso gli elementi e verifica se sono diversi
  for (let i = 0; i < obj1.length; i++) {
    const keys1 = Object.keys(obj1[i]);
    const keys2 = Object.keys(obj2[i]);

    // Verifica se il numero di proprietà è diverso
    if (keys1.length !== keys2.length) {
      return true;
    }

    // Verifica se le proprietà e i valori sono diversi
    for (let j = 0; j < keys1.length; j++) {
      const key = keys1[j];
      if (obj1[i][key] !== obj2[i][key]) {
        return true;
      }
    }
  }

  // Gli array di oggetti sono uguali
  return false;
}


var oldObj=[];

function updateChart() {
  $('.count').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).parent('.bar').attr('data-percent')
    }, {
        duration: 2000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now) +'%');
        }
    });
  });

  var holder = document.querySelector('.holder');
  const maxlen=15;
  // Rimuovi le barre di avanzamento esistenti
  fetch('get_data.php')
    .then(response => response.json())
    .then(data => {
      console.log(data)
      if(areObjectsDifferent(oldObj,data)){
        holder.innerHTML = '';
      
        oldObj=data;
        // Aggiorna le barre di avanzamento
        data.forEach(skill => {
          const bar = document.createElement('div');
          bar.classList.add('bar', 'cf');
          bar.setAttribute('data-percent', (skill.level*10) + '%');
          //let name=skill.team_name.length>15?skill.team_name.substr(0,15):skill.team_name;
          let name=skill.team_name;
          if(name.length>maxlen)
            name=name.substring(0,maxlen)+"...";
            bar.innerHTML = `<span class="label">${name} <br>Level: ${skill.level}</span><span class="label2">${skill.level*10}%</span>`;
          holder.appendChild(bar);
            
          setTimeout(function(){
            //bar.css('width', bar.getAttribute('data-percent'));      
            bar.style.width = (skill.level*10) + '%';  
          }, skill.level*100);
        });
      }
    })
    .catch(error => console.error('Error fetching data:', error));
}

function checkSession() {
  fetch('is_logged_in.php')
    .then(response => response.json())
    .then(data => {
      if (data.res) {
        refresh_session();
      } else {
        const page = window.location.pathname.split("/")[window.location.pathname.split("/").length - 1];
        console.log(page.length)
        window.location.replace("signin.php?page="+(page.length > 0 ? page : "index.html"))
      }
    })
    .catch(error => console.error('Error fetching data:', error));
}

function refresh_session(){
  setInterval(() => {
    fetch('refresh_session.php')
    .then(response => response.json())
    .catch(error => console.error('Error fetching data:', error));
  }, 5000);
}

checkSession()
updateChart()
setInterval(updateChart, 5000);
    