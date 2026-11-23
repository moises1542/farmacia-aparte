function initSidebarToggle() {
  const sidebar = document.getElementById('sidebar');
  if (!sidebar) return;

  const overlay = document.createElement('div');
  overlay.className = 'sidebar-overlay';
  document.body.appendChild(overlay);

  const isDesktop = () => window.innerWidth >= 992;

  const closeMobile = () => {
    sidebar.classList.remove('sidebar-open');
    overlay.classList.remove('show');
  };

  document.querySelectorAll('[data-sidebar-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (isDesktop()) {
        sidebar.classList.toggle('sidebar-collapsed');
      } else {
        if (sidebar.classList.contains('sidebar-open')) {
          closeMobile();
        } else {
          sidebar.classList.add('sidebar-open');
          overlay.classList.add('show');
        }
      }
    });
  });

  document.getElementById('sidebarClose')?.addEventListener('click', closeMobile);
  overlay.addEventListener('click', closeMobile);
}

function initUserDropdown() {
  const toggle = document.getElementById('userMenuToggle');
  if (!toggle) return;

  new bootstrap.Dropdown(toggle, {
    popperConfig(defaultConfig) {
      return { ...defaultConfig, strategy: 'fixed' };
    }
  });

  const chevron = toggle.querySelector('.user-menu-chevron');
  if (chevron) {
    toggle.addEventListener('show.bs.dropdown', () => chevron.classList.add('rotated'));
    toggle.addEventListener('hide.bs.dropdown', () => chevron.classList.remove('rotated'));
  }
}

function initLogoutConfirm() {
  const form = document.getElementById('logoutForm');
  if (!form) return;

  form.addEventListener('submit', () => {
    const button = document.getElementById('confirmLogoutBtn');
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>Saliendo...';
  });
}

function initTooltips() {
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
    new bootstrap.Tooltip(el);
  });
}

function initLoginForm() {
  const form = document.getElementById('loginForm');
  if (!form) return;

  const password = document.getElementById('loginPassword');
  const toggle = document.getElementById('togglePassword');
  toggle?.addEventListener('click', () => {
    const visible = password.type === 'text';
    password.type = visible ? 'password' : 'text';
    toggle.setAttribute('aria-label', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
    toggle.querySelector('i').className = `bi ${visible ? 'bi-eye' : 'bi-eye-slash'} text-secondary`;
  });

  form.addEventListener('submit', (e) => {
    document.getElementById('loginAlert')?.classList.add('d-none');

    if (!form.checkValidity()) {
      e.preventDefault();
      form.classList.add('was-validated');
      return;
    }

    const submit = document.getElementById('loginSubmit');
    submit.disabled = true;
    submit.querySelector('.spinner-border').classList.remove('d-none');
    submit.querySelector('.btn-label').textContent = 'Ingresando...';
  });
}

const storage = {
  get(key) {
    try { return localStorage.getItem(key); } catch (error) { return null; }
  },
  set(key, value) {
    try { localStorage.setItem(key, value); } catch (error) { return; }
  }
};

const motionReduced = () =>
  document.documentElement.classList.contains('no-motion') ||
  window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const normalizeText = (text) =>
  text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();

function initPreferences() {
  const root = document.documentElement;
  const sidebar = document.getElementById('sidebar');
  const animations = document.getElementById('prefAnimations');
  const collapsed = document.getElementById('prefSidebar');

  root.classList.toggle('no-motion', storage.get('sjb.animations') === 'off');
  if (sidebar && storage.get('sjb.sidebar') === 'collapsed' && window.innerWidth >= 992) {
    sidebar.style.transition = 'none';
    sidebar.classList.add('sidebar-collapsed');
    void sidebar.offsetWidth;
    sidebar.style.transition = '';
  }

  if (animations) {
    animations.checked = storage.get('sjb.animations') !== 'off';
    animations.addEventListener('change', () => {
      storage.set('sjb.animations', animations.checked ? 'on' : 'off');
      root.classList.toggle('no-motion', !animations.checked);
    });
  }

  if (collapsed) {
    collapsed.checked = storage.get('sjb.sidebar') === 'collapsed';
    collapsed.addEventListener('change', () => {
      storage.set('sjb.sidebar', collapsed.checked ? 'collapsed' : 'open');
    });
  }
}

function initCounters() {
  document.querySelectorAll('[data-counter]').forEach((el) => {
    const target = parseFloat(el.dataset.counter) || 0;
    const decimals = parseInt(el.dataset.decimals || '0', 10);
    const prefix = el.dataset.prefix || '';
    const format = (value) => prefix + value.toLocaleString('en-US', {
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals
    });

    if (motionReduced()) {
      el.textContent = format(target);
      return;
    }

    const duration = 900;
    const start = performance.now();
    const step = (now) => {
      const progress = Math.min(Math.max((now - start) / duration, 0), 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = format(target * eased);
      if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  });
}

function initPrintCounters() {
  const counters = Array.from(document.querySelectorAll('[data-counter]'));
  if (!counters.length) return;

  const copies = counters.map((el) => el.cloneNode(true));

  window.addEventListener('beforeprint', () => {
    counters.forEach((el, index) => el.replaceWith(copies[index]));
  }, { once: true });
}

function initTableSearch() {
  const table = document.getElementById('display');
  if (!table || !table.tBodies.length) return;

  const rows = Array.from(table.tBodies[0].rows);
  const empty = document.createElement('div');
  empty.className = 'table-empty d-none';
  empty.innerHTML = '<i class="bi bi-inbox"></i><span></span>';
  table.insertAdjacentElement('afterend', empty);

  const showEmpty = (text) => {
    empty.querySelector('span').textContent = text;
    empty.classList.remove('d-none');
  };

  if (!rows.length) {
    showEmpty('Todavía no hay registros.');
    return;
  }

  const box = document.createElement('div');
  box.className = 'table-search input-group input-group-sm mb-3';
  box.innerHTML = '<span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>' +
    '<input type="search" class="form-control border-start-0" placeholder="Buscar..." aria-label="Buscar en el listado">';
  table.insertAdjacentElement('beforebegin', box);

  const texts = rows.map((row) => normalizeText(
    Array.from(row.cells).slice(0, -1).map((cell) => cell.textContent).join(' ')
  ));

  box.querySelector('input').addEventListener('input', (e) => {
    const term = normalizeText(e.target.value);
    let visible = 0;

    rows.forEach((row, index) => {
      const match = texts[index].includes(term);
      row.classList.toggle('d-none', !match);
      if (match) visible++;
    });

    if (visible) {
      empty.classList.add('d-none');
    } else {
      showEmpty('Sin resultados para la búsqueda.');
    }
  });
}

function initRowMenus() {
  document.querySelectorAll('#display [data-bs-toggle="dropdown"]').forEach((button) => {
    new bootstrap.Dropdown(button, {
      popperConfig(defaultConfig) {
        return { ...defaultConfig, strategy: 'fixed' };
      }
    });
  });
}

function initModals() {
  document.querySelectorAll('.modal[data-auto-open]').forEach((modal) => {
    bootstrap.Modal.getOrCreateInstance(modal).show();
  });

  document.querySelectorAll('.toast').forEach((toast) => {
    bootstrap.Toast.getOrCreateInstance(toast).show();
  });

  document.addEventListener('show.bs.modal', () => {
    document.getElementById('sidebar')?.classList.remove('sidebar-open');
    document.querySelector('.sidebar-overlay')?.classList.remove('show');
  });
}

function initPrintButtons() {
  document.querySelectorAll('[data-print]').forEach((button) => {
    button.addEventListener('click', () => window.print());
  });
}

function initSubmitButtons() {
  document.querySelectorAll('form[method="POST"]:not(#loginForm):not(#logoutForm)').forEach((form) => {
    form.addEventListener('submit', (e) => {
      if (e.defaultPrevented) return;

      form.querySelectorAll('input[type="number"]').forEach((input) => {
        if (Number.isInteger(input.valueAsNumber)) input.value = input.valueAsNumber;
      });

      const button = form.querySelector('button[type="submit"]');
      if (button) setTimeout(() => { button.disabled = true; }, 0);
    });
  });

  window.addEventListener('pageshow', (e) => {
    if (!e.persisted) return;

    document.querySelectorAll('form button[type="submit"]:disabled').forEach((button) => {
      button.disabled = false;
    });
  });
}

function initSaleForm() {
  const form = document.getElementById('saleForm');
  if (!form) return;

  const items = Array.from(document.querySelectorAll('.medicine-item'));
  const search = document.getElementById('medicineSearch');
  const body = document.getElementById('saleItems');
  const template = document.getElementById('saleRow');
  const submit = form.querySelector('button[type="submit"]');
  const rate = parseFloat(form.dataset.igv);

  const money = (cents) => 'S/ ' + (cents / 100).toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
  const rowOf = (item) => Array.from(body.rows).find((row) => row.dataset.id === item.dataset.id);
  const inputOf = (row) => row.querySelector('input[type="number"]');
  const amountOf = (row) => Math.max(Math.trunc(inputOf(row).valueAsNumber) || 0, 0);

  const refresh = () => {
    let total = 0;

    Array.from(body.rows).forEach((row) => {
      const subtotal = amountOf(row) * Number(row.dataset.cents);
      row.querySelector('[data-cell="subtotal"]').textContent = money(subtotal);
      inputOf(row).classList.toggle('border-danger', !inputOf(row).checkValidity());
      total += subtotal;
    });

    const igv = Math.round(total - total / (1 + rate));
    document.getElementById('saleTaxable').textContent = money(total - igv);
    document.getElementById('saleIgv').textContent = money(igv);
    document.getElementById('saleTotal').textContent = money(total);

    items.forEach((item) => {
      const row = rowOf(item);
      const badge = item.querySelector('[data-added]');
      item.classList.toggle('border-primary', Boolean(row));
      badge.classList.toggle('d-none', !row);
      badge.textContent = row ? `× ${amountOf(row)}` : '';
    });

    document.getElementById('saleEmpty').classList.toggle('d-none', body.rows.length > 0);
    submit.disabled = !body.rows.length;
  };

  const addItem = (item, amount = 1) => {
    let row = rowOf(item);

    if (row) {
      inputOf(row).value = Math.min(amountOf(row) + 1, Number(item.dataset.stock));
    } else {
      row = template.content.firstElementChild.cloneNode(true);
      row.dataset.id = item.dataset.id;
      row.dataset.cents = Math.round(parseFloat(item.dataset.price) * 100);
      row.querySelector('input[type="hidden"]').value = item.dataset.id;
      row.querySelector('[data-cell="name"]').textContent = item.dataset.name;
      row.querySelector('[data-cell="stock"]').textContent = `Stock: ${item.dataset.stock}`;
      row.querySelectorAll('[data-cell="price"]').forEach((cell) => {
        cell.textContent = money(Number(row.dataset.cents));
      });
      inputOf(row).max = item.dataset.stock;
      inputOf(row).value = amount;
      body.appendChild(row);
    }

    refresh();
    return row;
  };

  const texts = items.map((item) => normalizeText(`${item.dataset.name} ${item.dataset.category}`));

  search.addEventListener('input', () => {
    const term = normalizeText(search.value);
    let visible = 0;

    items.forEach((item, index) => {
      const match = texts[index].includes(term);
      item.classList.toggle('d-none', !match);
      if (match) visible++;
    });

    document.getElementById('medicineEmpty').classList.toggle('d-none', visible > 0);
  });

  items.forEach((item) => {
    item.addEventListener('click', () => {
      const row = addItem(item);
      row.classList.add('row-flash');
      row.addEventListener('animationend', () => row.classList.remove('row-flash'), { once: true });
    });
  });

  body.addEventListener('input', refresh);
  body.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && e.target.matches('input')) e.preventDefault();
  });
  body.addEventListener('click', (e) => {
    const button = e.target.closest('button');
    if (!button) return;

    button.closest('tr').remove();
    refresh();
  });

  document.getElementById('saleCustomer').addEventListener('click', (e) => {
    if (body.rows.length && !confirm('Se perderá el detalle de la venta. ¿Continuar?')) e.preventDefault();
  });

  form.addEventListener('submit', (e) => {
    if (!body.rows.length) e.preventDefault();

    Array.from(body.rows).map(inputOf).forEach((input) => {
      if (Number.isInteger(input.valueAsNumber)) input.value = input.valueAsNumber;
    });
  });

  const amounts = JSON.parse(body.dataset.amounts) || [];
  [].concat(JSON.parse(body.dataset.medicines)).forEach((id, index) => {
    const item = items.find((el) => el.dataset.id === String(id));
    if (item) addItem(item, amounts[index]);
  });

  refresh();
}

document.addEventListener('DOMContentLoaded', () => {
  initPreferences();
  initSidebarToggle();
  initUserDropdown();
  initLogoutConfirm();
  initTooltips();
  initLoginForm();
  initCounters();
  initPrintCounters();
  initTableSearch();
  initRowMenus();
  initModals();
  initPrintButtons();
  initSaleForm();
  initSubmitButtons();
});
