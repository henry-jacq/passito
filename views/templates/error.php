<div class="flex items-center justify-center min-h-screen">
    <div class="p-6 md:p-10">
        <div class="flex flex-col items-center justify-center py-12 bg-gray-100 rounded-lg text-center">
            <span class="text-6xl block"><?= $code ?></span>
            <span class="text-4xl block mb-3">Error</span>
            <div class="mb-4 text-lg px-5 sm:px-10">The page you are looking for was not found or other error occurred.</div>
            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 mx-auto mt-4">
                <a href="<?= $this->urlFor('auth.login') ?>" class="btn text-body border-0 text-decoration-none hover:bg-gray-200 px-6 py-2 rounded-md transition duration-300">
                    <i class="fa fa-arrow-left mr-2"></i>Go Back
                </a>
            </div>
        </div>
    </div>
</div>