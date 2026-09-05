// js/pasarela-pago.js

/**
 * Algoritmo de Luhn (Mod 10) para validar números de tarjeta
 */
function isValidLuhn(number) {
    let sum = 0;
    let shouldDouble = false;
    for (let i = number.length - 1; i >= 0; i--) {
        let digit = parseInt(number.charAt(i));
        if (shouldDouble) {
            if ((digit *= 2) > 9) digit -= 9;
        }
        sum += digit;
        shouldDouble = !shouldDouble;
    }
    return (sum % 10) === 0;
}

/**
 * Detecta la marca de la tarjeta (Visa, Mastercard, Amex)
 */
function getCardBrand(number) {
    if (/^4/.test(number)) return 'VISA';
    if (/^5[1-5]/.test(number) || /^2[2-7]/.test(number)) return 'MASTERCARD';
    if (/^3[47]/.test(number)) return 'AMEX';
    return 'TARJETA';
}

/**
 * Abre el modal del Simulador Bancario
 * Retorna una Promise que se resuelve con el JSON de la transacción o se rechaza si cancela
 */
function abrirModalPago({ monto, concepto, equipo }) {
    return new Promise((resolve, reject) => {
        // Crear elementos del modal
        const overlay = document.createElement('div');
        overlay.className = 'pasarela-modal-overlay';

        const modalHTML = `
            <div class="pasarela-modal">
                <h2>PAGO SEGURO</h2>
                
                <div class="tarjeta-grafica" id="tarjetaGrafica">
                    <div class="marca" id="tg-marca">VISA</div>
                    <div class="chip"></div>
                    <div class="numero" id="tg-numero">•••• •••• •••• ••••</div>
                    <div class="datos-inferiores">
                        <div class="titular" id="tg-titular">TITULAR</div>
                        <div class="expiracion" id="tg-exp">MM/AA</div>
                    </div>
                </div>

                <form id="pasarelaForm" class="pasarela-form">
                    <p style="text-align:center; font-weight:bold; font-size:1.2rem; margin-bottom:15px;">Monto a pagar: $${parseFloat(monto).toFixed(2)} MXN</p>
                    
                    <div class="form-group">
                        <label>Número de Tarjeta</label>
                        <input type="text" id="cc-numero" placeholder="0000 0000 0000 0000" maxlength="19" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label>Nombre del Titular</label>
                        <input type="text" id="cc-titular" placeholder="EJ. JUAN PEREZ" required autocomplete="off" style="text-transform:uppercase;">
                    </div>

                    <div class="row">
                        <div class="form-group" style="flex:1;">
                            <label>Expiración</label>
                            <input type="text" id="cc-exp" placeholder="MM/AA" maxlength="5" required autocomplete="off">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>CVV</label>
                            <input type="password" id="cc-cvv" placeholder="123" maxlength="4" required autocomplete="off">
                        </div>
                    </div>

                    <button type="submit" class="btn-pagar">AUTORIZAR PAGO</button>
                    <button type="button" class="btn-cancelar" id="btnCancelarPago">Cancelar</button>
                </form>

                <div class="pasarela-loading" id="pasarelaLoading">
                    <div class="spinner"></div>
                    <h3>Contactando banco emisor...</h3>
                    <p>Por favor, no cierres esta ventana.</p>
                </div>
            </div>
        `;

        overlay.innerHTML = modalHTML;
        document.body.appendChild(overlay);

        // Referencias a elementos
        const form = document.getElementById('pasarelaForm');
        const loading = document.getElementById('pasarelaLoading');
        const inputNum = document.getElementById('cc-numero');
        const inputTitular = document.getElementById('cc-titular');
        const inputExp = document.getElementById('cc-exp');
        
        const tgNum = document.getElementById('tg-numero');
        const tgTitular = document.getElementById('tg-titular');
        const tgExp = document.getElementById('tg-exp');
        const tgMarca = document.getElementById('tg-marca');

        // Formateo y binding en tiempo real
        inputNum.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            if (val.length > 0) {
                val = val.match(/.{1,4}/g).join(' ');
            }
            e.target.value = val;
            tgNum.textContent = val || '•••• •••• •••• ••••';
            tgMarca.textContent = getCardBrand(val.replace(/\s/g, ''));
        });

        inputTitular.addEventListener('input', (e) => {
            tgTitular.textContent = e.target.value.toUpperCase() || 'TITULAR';
        });

        inputExp.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            if (val.length > 2) {
                val = val.substring(0,2) + '/' + val.substring(2,4);
            }
            e.target.value = val;
            tgExp.textContent = val || 'MM/AA';
        });

        document.getElementById('btnCancelarPago').addEventListener('click', () => {
            document.body.removeChild(overlay);
            reject(new Error("Pago cancelado por el usuario"));
        });

        // Submit del formulario
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const numLimpio = inputNum.value.replace(/\s/g, '');
            if (!isValidLuhn(numLimpio)) {
                alert("❌ Número de tarjeta inválido. Verifica los dígitos según el estándar bancario.");
                return;
            }
            
            if (numLimpio === '4000000000009999') {
                alert("❌ Transacción rechazada: Fondos insuficientes (Simulación).");
                return;
            }

            // Iniciar carga
            form.style.display = 'none';
            loading.style.display = 'block';

            // Simular respuesta bancaria (1.5 segundos)
            setTimeout(() => {
                document.body.removeChild(overlay);
                
                const marca = getCardBrand(numLimpio);
                const ultimosDigitos = numLimpio.slice(-4);
                const authCode = 'AUTH-' + Math.floor(Math.random() * 900000 + 100000);
                const folioTxn = 'TXN-' + new Date().getTime();

                // Resolver con el JSON simulado de respuesta
                resolve({
                    aprobado: true,
                    folio: folioTxn,
                    autorizacion_bancaria: authCode,
                    marca: marca,
                    ultimos_digitos: ultimosDigitos,
                    titular: inputTitular.value.toUpperCase(),
                    monto: monto,
                    concepto: concepto,
                    equipo_nombre: equipo,
                    metodo_pago: "Tarjeta " + marca + " terminación **** " + ultimosDigitos
                });
            }, 1500);
        });
    });
}
