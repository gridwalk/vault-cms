outputLink = function(link){
  filename = link.abs_path.replace(/^.*[\\\/]/, '').replace('.webloc','')
  _a = document.createElement('a')
  _a.href = link.link[0]
  _a.innerHTML = filename
  _container.appendChild(_a)
}