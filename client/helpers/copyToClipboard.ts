export default function copy(text: string): Promise<void> {
  if (navigator.clipboard && window.isSecureContext) {
    // Utiliza a API Clipboard
    return navigator.clipboard.writeText(text);
  }
  else {
    const previouslyFocusedElement = document.activeElement as HTMLElement;

    // Método de fallback para navegadores que não suportam a API Clipboard
    let element = document.createElement("textarea");
    element.value = text;

    element.setAttribute('readonly', '');

    // Evita que o textarea seja exibido na tela
    element.style.contain = 'strict';
    element.style.position = 'absolute';
    element.style.left = '-9999px';
    element.style.fontSize = '12pt'; // Prevent zooming on iOS

    const selection = document.getSelection();
    const originalRange = selection!.rangeCount > 0 && selection!.getRangeAt(0);

    document.body.appendChild(element);
    element.focus();
    element.select();

    // Explicit selection workaround for iOS
    element.selectionStart = 0;
    element.selectionEnd = text.length;

    return new Promise(function(resolve, reject) {
        // Tenta copiar o texto
        try {
            let successful = document.execCommand('copy');
            if (successful) {
                resolve();
            } else {
                reject();
            }
        } catch (err) {
            reject(err);
        } finally {
            element.remove();

            if (originalRange && selection) {
              selection.removeAllRanges();
              selection.addRange(originalRange);
            }

            // Get the focus back on the previously focused element, if any
            if (previouslyFocusedElement) {
              previouslyFocusedElement.focus();
            }
        }
    });
  }
}
