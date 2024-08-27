<?php if (isset($_SESSION['flash_messages']['success'])): ?>
    <div
        id="success-alert"
        class="fixed top-2 right-0 my-24 mx-8 z-10 flex items-center p-4 mb-4 border-t-4
        text-green-800  border-[#3A8340] bg-[#D4ECCE]"
        role="alert">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-8">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <div class="ms-3 text-sm font-medium">
            <h5 class="font-bold text-sm">Excelente</h5>
            <p class="font-normal text-sm">
                <?php foreach ($_SESSION['flash_messages']['success'] as $message): ?>
                    {!! $message !!}
                <?php endforeach; ?>
            </p>
        </div>

        <button
            type="button"
            class="ms-auto -mx-1.5 -mt-8 bg-green-100 text-green-800 rounded-lg focus:ring-2
            focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center
            justify-center h-8 w-8" onclick="this.parentElement.style.display='none'">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round"
                    stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-alert').style.display = 'none';
        }, 3000);
    </script>
    <?php unset($_SESSION['flash_messages']['success']); ?>
<?php endif; ?>
