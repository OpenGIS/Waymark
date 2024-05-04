export function makeKey(str) {
  if (!str) {
    return str
  }

  //No underscores
  str = str.replace(/[^a-z0-9+]+/gi, '')

  //Lower
  str = str.toLowerCase()

  return str
}

export function visibleIcon(isVisble) {
  return isVisble ? 'ion-eye' : 'ion-eye-disabled'
}

export function expandedIcon(isExpanded) {
  return isExpanded ? 'fa-angle-double-up' : 'fa-angle-double-down'
}
