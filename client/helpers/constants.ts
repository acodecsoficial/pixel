export const isTouchCapable = 'ontouchstart' in window ||
    // @ts-ignore
  (window.DocumentTouch && document instanceof window.DocumentTouch) ||
  // @ts-ignore
  navigator.maxTouchPoints > 0 ||
  // @ts-ignore
  window.navigator.msMaxTouchPoints > 0;


export const defaultAvatar = 'https://ik.imagekit.io/xgaming/unknown.svg?updatedAt=1724267541592'
