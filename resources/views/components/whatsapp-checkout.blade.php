<button type="button" class="btn-black w-full" @click="exportOrder()" :disabled="exporting">
    <span x-text="exporting ? 'COMPILING…' : 'COMPILE ORDER & EXPORT TO WHATSAPP'">COMPILE ORDER &amp; EXPORT TO WHATSAPP</span>
</button>
