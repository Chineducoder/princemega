<div class="mt-5 border border-dashed border-ink p-4" x-data="receiptUpload()">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="micro">ATTACH TRANSFER RECEIPT</p>
            <p class="mt-1 text-2xs text-mid">Mobile bank transfer receipt · Priority warehouse packing queue</p>
        </div>
        <label class="micro shrink-0 cursor-pointer border border-ink px-3 py-2 transition-colors duration-200 hover:bg-ink hover:text-paper">
            Browse
            <input
                type="file"
                class="sr-only"
                accept="image/jpeg,image/png,image/webp,application/pdf"
                x-ref="file"
                @change="change($event)"
            >
        </label>
    </div>
    <p class="micro mt-3 text-mid" x-show="name" x-cloak>
        <span x-text="name"></span> · <span x-text="size"></span>
        <button type="button" class="ml-2 underline" @click="clear($event)">REMOVE</button>
    </p>
</div>
