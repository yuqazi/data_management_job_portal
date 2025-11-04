// app.js
// Toggle visual state for checkbox buttons (and for dynamically added ones).
// Works with Bootstrap-style "btn-check" inputs + <label class="btn"> pairs,
// and with plain checkboxes where the related label/button has a data-for or
// is the next sibling.

(function(){
    'use strict';

    // Utility: find label element for an input
    function findAssociatedLabel(input){
        // If input has id, try querySelector for label[for=id]
        if(input.id){
            var lbl = document.querySelector('label[for="' + CSS.escape(input.id) + '"]');
            if(lbl) return lbl;
        }
        // Otherwise, check nextElementSibling (Bootstrap uses input + label)
        var next = input.nextElementSibling;
        if(next && (next.tagName.toLowerCase() === 'label' || next.classList.contains('btn'))){
            return next;
        }
        // Or previousElementSibling
        var prev = input.previousElementSibling;
        if(prev && (prev.tagName.toLowerCase() === 'label' || prev.classList.contains('btn'))){
            return prev;
        }
        return null;
    }

    // Given a checkbox/radio input, update its associated button/label active state
    function syncInputToLabel(input){
        var lbl = findAssociatedLabel(input);
        if(!lbl) return;
        if(input.type === 'checkbox' || input.type === 'radio'){
            if(input.checked){
                lbl.classList.add('active');
            } else {
                lbl.classList.remove('active');
            }
            // Also set aria-pressed for accessibility on label if role button
            if(lbl.getAttribute('role') === 'button' || lbl.classList.contains('btn')){
                lbl.setAttribute('aria-pressed', input.checked ? 'true' : 'false');
            }
        }
    }

    // Initialize existing inputs
    function initExisting(){
        var inputs = document.querySelectorAll('input[type="checkbox"], input[type="radio"]');
        inputs.forEach(function(i){
            syncInputToLabel(i);
        });
    }

    // Event delegation: listen for changes on document and update labels
    document.addEventListener('change', function(e){
        var tgt = e.target;
        if(!tgt) return;
        if(tgt.matches('input[type="checkbox"], input[type="radio"]')){
            syncInputToLabel(tgt);
        }
    }, false);

    // Also handle clicks on labels that may toggle inputs without firing change (rare)
    document.addEventListener('click', function(e){
        var el = e.target;
        if(!el) return;
        // If clicked element is a label with a for attribute, locate input and toggle
        if(el.tagName && el.tagName.toLowerCase() === 'label'){
            var forAttr = el.getAttribute('for');
            if(forAttr){
                var input = document.getElementById(forAttr);
                if(input && (input.type === 'checkbox' || input.type === 'radio')){
                    // Let browser toggle; schedule sync shortly after
                    setTimeout(function(){ syncInputToLabel(input); }, 0);
                }
            } else {
                // If label wraps the input, find child input
                var inner = el.querySelector('input[type="checkbox"], input[type="radio"]');
                if(inner){ setTimeout(function(){ syncInputToLabel(inner); }, 0); }
            }
        }
    }, false);

    // Watch for dynamically added inputs/labels and initialize them
    var observer = new MutationObserver(function(mutations){
        mutations.forEach(function(m){
            m.addedNodes.forEach(function(node){
                if(!(node instanceof Element)) return;
                // If a checkbox/radio added directly
                if(node.matches && node.matches('input[type="checkbox"], input[type="radio"]')){
                    syncInputToLabel(node);
                }
                // Or if contains such inputs
                var inner = node.querySelectorAll && node.querySelectorAll('input[type="checkbox"], input[type="radio"]');
                if(inner && inner.length){
                    inner.forEach(function(i){ syncInputToLabel(i); });
                }
            });
        });
    });

    observer.observe(document.documentElement || document.body, { childList: true, subtree: true });

    // Run initial pass on DOMContentLoaded or immediately if already loaded
    if(document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', initExisting);
    } else {
        initExisting();
    }

})();
