{{-- Shared admin UI styles: toasts, the media uploader and the variant builder. --}}
<style>
   /* DashLite's fixed header overshoots the viewport by a few px on narrow
      screens, which shows a stray horizontal scrollbar. */
   body.nk-body { overflow-x: hidden; }

   /* ---------------------------------------------------------------- toasts */
   .pv-toast-stack {
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 2000;
      display: flex;
      flex-direction: column;
      gap: .5rem;
      max-width: min(28rem, calc(100vw - 2rem));
   }
   .pv-toast {
      display: flex;
      align-items: flex-start;
      gap: .625rem;
      padding: .75rem 1rem;
      border-radius: .5rem;
      background: #fff;
      color: #364a63;
      box-shadow: 0 .5rem 1.5rem rgba(43, 55, 72, .18);
      border-left: 4px solid #6576ff;
      font-size: .875rem;
      animation: pv-toast-in .25s ease;
   }
   .pv-toast.is-leaving { opacity: 0; transform: translateX(1rem); transition: all .25s ease; }
   .pv-toast-success { border-left-color: #1ee0ac; }
   .pv-toast-success .icon { color: #1ee0ac; }
   .pv-toast-error { border-left-color: #e85347; }
   .pv-toast-error .icon { color: #e85347; }
   .pv-toast-warning { border-left-color: #f4bd0e; }
   .pv-toast-warning .icon { color: #f4bd0e; }
   .pv-toast-info .icon { color: #6576ff; }
   .pv-toast span { flex: 1; }
   .pv-toast-close {
      border: 0; background: none; color: #8094ae;
      font-size: 1.25rem; line-height: 1; cursor: pointer; padding: 0;
   }
   @keyframes pv-toast-in { from { opacity: 0; transform: translateX(1rem); } to { opacity: 1; transform: none; } }

   /* --------------------------------------------------------- media uploader */
   .pv-dropzone {
      border: 2px dashed #dbdfea;
      border-radius: .75rem;
      background: #f8f9fc;
      padding: 2rem 1rem;
      text-align: center;
      cursor: pointer;
      transition: border-color .15s ease, background .15s ease;
   }
   .pv-dropzone:hover,
   .pv-dropzone:focus-visible { border-color: #6576ff; background: #f4f6ff; }
   .pv-dropzone.is-dragging { border-color: #6576ff; background: #eef1ff; }
   .pv-dropzone-icon { font-size: 2rem; color: #8094ae; display: block; margin-bottom: .5rem; }
   .pv-dropzone-title { font-weight: 600; color: #364a63; }
   .pv-dropzone-hint { font-size: .8125rem; color: #8094ae; margin-top: .25rem; }

   .pv-media-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: .75rem;
      margin-top: 1rem;
   }
   .pv-media-item {
      position: relative;
      border: 1px solid #dbdfea;
      border-radius: .5rem;
      overflow: hidden;
      background: #fff;
      aspect-ratio: 1;
      cursor: grab;
   }
   .pv-media-item.is-dragging { opacity: .4; }
   .pv-media-item.is-drop-target { outline: 2px solid #6576ff; outline-offset: 2px; }
   .pv-media-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
   .pv-media-item.is-featured { border-color: #6576ff; box-shadow: 0 0 0 2px rgba(101, 118, 255, .25); }
   .pv-media-badge {
      position: absolute; top: .375rem; left: .375rem;
      background: #6576ff; color: #fff;
      font-size: .625rem; font-weight: 600; letter-spacing: .02em;
      padding: .125rem .375rem; border-radius: .25rem;
   }
   .pv-media-actions {
      position: absolute; inset: auto 0 0 0;
      display: flex; gap: .25rem; justify-content: center;
      padding: .375rem;
      background: linear-gradient(transparent, rgba(28, 43, 70, .75));
      opacity: 0; transition: opacity .15s ease;
   }
   .pv-media-item:hover .pv-media-actions,
   .pv-media-item:focus-within .pv-media-actions { opacity: 1; }
   .pv-media-actions button {
      border: 0; border-radius: .25rem; padding: .1875rem .375rem;
      font-size: .75rem; line-height: 1.2; cursor: pointer;
      background: rgba(255, 255, 255, .9); color: #364a63;
   }
   .pv-media-actions button:hover { background: #fff; }
   .pv-media-actions button.pv-media-remove:hover { background: #e85347; color: #fff; }
   .pv-media-progress {
      position: absolute; inset: 0;
      display: flex; align-items: center; justify-content: center;
      background: rgba(255, 255, 255, .82); font-size: .75rem; color: #6576ff;
   }
   .pv-media-empty {
      text-align: center; color: #8094ae; font-size: .8125rem; padding: 1rem 0;
   }

   /* ------------------------------------------------------- variant builder */
   .pv-variant-table { --pv-variant-cols: 2.5rem 1.4fr 1fr 1.2fr .9fr .9fr .8rem; }
   .pv-variant-head,
   .pv-variant-row {
      display: grid;
      grid-template-columns: var(--pv-variant-cols);
      gap: .5rem;
      align-items: center;
   }
   .pv-variant-head {
      font-size: .75rem; font-weight: 600; text-transform: uppercase;
      letter-spacing: .04em; color: #8094ae;
      padding: 0 .25rem .5rem;
      border-bottom: 1px solid #dbdfea;
   }
   .pv-variant-row {
      padding: .5rem .25rem;
      border-bottom: 1px solid #f0f2f7;
   }
   .pv-variant-row.is-inactive { opacity: .55; }
   .pv-variant-row .form-control,
   .pv-variant-row .form-select { font-size: .8125rem; }
   .pv-swatch {
      width: 2rem; height: 2rem; padding: 0;
      border: 1px solid #dbdfea; border-radius: .375rem;
      cursor: pointer; background: none;
   }
   .pv-variant-remove {
      border: 0; background: none; color: #8094ae;
      font-size: 1.125rem; line-height: 1; cursor: pointer; padding: 0 .25rem;
   }
   .pv-variant-remove:hover { color: #e85347; }
   .pv-chip-input {
      display: flex; flex-wrap: wrap; gap: .375rem;
      border: 1px solid #dbdfea; border-radius: .375rem;
      padding: .375rem .5rem; min-height: 2.625rem; background: #fff;
   }
   .pv-chip {
      display: inline-flex; align-items: center; gap: .375rem;
      background: #f0f2f7; border-radius: 1rem;
      padding: .1875rem .5rem; font-size: .8125rem; color: #364a63;
   }
   .pv-chip button { border: 0; background: none; color: #8094ae; cursor: pointer; line-height: 1; padding: 0; }
   .pv-chip button:hover { color: #e85347; }
   .pv-chip-input input {
      flex: 1; min-width: 8rem; border: 0; outline: 0; font-size: .8125rem; background: transparent;
   }
   .pv-chip-dot {
      flex: 0 0 auto;
      width: .875rem; height: .875rem;
      border-radius: 50%; border: 1px solid rgba(0, 0, 0, .12);
      padding: 0; background: none; cursor: pointer; overflow: hidden;
   }
   /* Chrome/Safari draw their own padded swatch inside a colour input. */
   .pv-chip-dot::-webkit-color-swatch-wrapper { padding: 0; }
   .pv-chip-dot::-webkit-color-swatch { border: 0; border-radius: 50%; }
   .pv-swatch::-webkit-color-swatch-wrapper { padding: 2px; }
   .pv-swatch::-webkit-color-swatch { border: 0; border-radius: .25rem; }

   @media (max-width: 767.98px) {
      .pv-variant-head { display: none; }
      .pv-variant-row {
         grid-template-columns: 1fr 1fr;
         gap: .5rem;
         padding: .75rem .25rem;
         border-bottom: 1px solid #dbdfea;
      }
      .pv-variant-row > *:first-child { grid-column: 1 / -1; }
   }

   /* -------------------------------------------------------- misc utilities */
   .pv-sticky-actions {
      position: sticky; bottom: 0; z-index: 10;
      background: #fff; border-top: 1px solid #dbdfea;
      padding: .875rem 1.25rem;
      display: flex; gap: .5rem; justify-content: flex-end; align-items: center;
      box-shadow: 0 -.25rem .75rem rgba(43, 55, 72, .06);
      border-radius: 0 0 .5rem .5rem;
   }
   .pv-section-title { font-size: .9375rem; font-weight: 600; color: #364a63; }
   .pv-section-hint { font-size: .8125rem; color: #8094ae; margin-top: .125rem; }
   .pv-stat-card { border-radius: .5rem; }
   .pv-stat-value { font-size: 1.5rem; font-weight: 700; color: #364a63; line-height: 1.2; }
   .pv-stat-label { font-size: .8125rem; color: #8094ae; }
   .pv-thumb {
      width: 44px; height: 44px; object-fit: cover;
      border-radius: .375rem; border: 1px solid #dbdfea; background: #f5f6fa;
   }
   /* Card list rows — plain markup, so DashLite's own list styles can't fight it. */
   .pv-list-row {
      display: flex; align-items: center; gap: .75rem;
      padding: .75rem 1.25rem;
      border-bottom: 1px solid #f0f2f7;
      line-height: 1.4;
   }
   .pv-list-row:last-child { border-bottom: 0; }
   .pv-list-main { flex: 1 1 auto; min-width: 0; }

   .pv-filter-bar { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
   .pv-filter-bar > * { flex: 0 0 auto; }
   .pv-filter-bar .form-select { width: auto; min-width: 8.5rem; }
   .pv-filter-bar .pv-search { flex: 1 1 16rem; min-width: 12rem; position: relative; }
   .pv-filter-bar .pv-search .form-control { width: 100%; padding-left: 2.25rem; }

   /* Row actions — DashLite squashes bare `.btn-icon` inside flex parents. */
   .pv-actions { display: inline-flex; align-items: center; gap: .25rem; }
   .pv-actions form { display: inline-flex; margin: 0; }
   .pv-actions .btn {
      display: inline-flex; align-items: center; justify-content: center;
      width: 2rem; height: 2rem; padding: 0; line-height: 1; flex: 0 0 auto;
   }
   .pv-actions .btn .icon { font-size: .875rem; line-height: 1; }
   .pv-status-toggle {
      border: 0; background: none; padding: 0; cursor: pointer;
   }
   .is-invalid ~ .invalid-feedback { display: block; }
</style>
