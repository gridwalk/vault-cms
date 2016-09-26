output_nav = function( pages ){
  _nav = document.createElement('nav')
  for (var i = 0; i < pages.length; i++) {

    if( pages[i] == '.DS_Store' ){ continue }

    lastSlash = pages[i].lastIndexOf('/')
    pageTitle = pages[i].substring(lastSlash+1)

    href = siteURL + '/' + pages[i].replace('pages/','')

    _a = document.createElement('a')
    _a.href = href
    _a.innerHTML = pageTitle
    _nav.appendChild(_a)
  }

  _target = document.querySelectorAll('body>nav')[0]
  if( !_target ){ _target = _body }

  _target.appendChild(_nav)
}

output_nav(pages)

// output subnavs
for( var subpage_path in subpages ){
  output_nav( subpages[subpage_path] )
}

// add space at the top of the page for the navs
navHeight = document.querySelectorAll('body>nav')[0].clientHeight
_body.style.paddingTop = navHeight

// highlight active page chain
pathArray = window.location.pathname.split( '/' )
_navLinks = document.querySelectorAll('nav a')
for (var i = 0; i < _navLinks.length; i++) {
  if(pathArray.indexOf(_navLinks[i].innerHTML) > -1 ){
    _navLinks[i].style.backgroundColor = 'rgba(255,255,255,.5)'
  }
}