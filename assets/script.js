(function ($) {
    function initPdfLibrary($scope) {
        const container = $scope.find('.pdf-library-container');
        if (!container.length) return;

        const id = container.data('widget-id');
        const enablePagination = container.data('enable-pagination') === 'yes';
        const paginationMode = container.data('pagination-mode');
        const itemsPerPage = parseInt(container.data('items-per-page'), 10) || 10;

        const searchInput = $scope.find('#pdfSearchInput-' + id)[0];
        const pdfList = $scope.find('#pdfList-' + id)[0];
        const viewer = $scope.find('#pdfViewer-' + id)[0];
        const paginationContainer = $scope.find('#pdfPagination-' + id)[0];

        if (!pdfList || !searchInput) return;

        // Remove category/subcategory headers if you want:
        $scope.find('.pdf-category-header, .pdf-subcategory-header').remove();

        let allItems = Array.from(pdfList.querySelectorAll('li'));
        let filteredItems = [...allItems];
        let currentPage = 1;

        function renderList() {
            pdfList.innerHTML = '';

            if (!enablePagination) {
                filteredItems.forEach(item => pdfList.appendChild(item));
                if (paginationContainer) paginationContainer.style.display = 'none';
                return;
            }

            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const itemsToShow = filteredItems.slice(start, end);

            itemsToShow.forEach(item => pdfList.appendChild(item));

            if (paginationContainer) {
                paginationContainer.style.display = '';
                renderPagination();
            }
        }

        function renderPagination() {
            if (!paginationContainer) return;
            paginationContainer.innerHTML = '';

            const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
            if (totalPages <= 1) return;

            if (paginationMode === 'numeric') {
                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className = (i === currentPage) ? 'active' : '';
                    btn.addEventListener('click', () => {
                        currentPage = i;
                        renderList();
                    });
                    paginationContainer.appendChild(btn);
                }
            }
            else if (paginationMode === 'load_more') {
                const btn = document.createElement('button');
                btn.textContent = 'Load More';
                btn.addEventListener('click', () => {
                    currentPage++;
                    const start = (currentPage - 1) * itemsPerPage;
                    const end = start + itemsPerPage;
                    const nextItems = filteredItems.slice(start, end);
                    nextItems.forEach(item => pdfList.appendChild(item));
                    if (currentPage >= totalPages) {
                        btn.style.display = 'none';
                    }
                });
                paginationContainer.appendChild(btn);
            }
            else if (paginationMode === 'infinite') {
                let loading = false;

                function onScroll() {
                    if (loading) return;
                    if (pdfList.scrollTop + pdfList.clientHeight >= pdfList.scrollHeight - 10) {
                        loading = true;
                        currentPage++;
                        const start = (currentPage - 1) * itemsPerPage;
                        const end = start + itemsPerPage;
                        const nextItems = filteredItems.slice(start, end);
                        nextItems.forEach(item => pdfList.appendChild(item));
                        if (currentPage >= totalPages) {
                            $scope.find('#pdfList-' + id).off('scroll', onScroll);
                        }
                        loading = false;
                    }
                }

                $scope.find('#pdfList-' + id).on('scroll', onScroll);
            }else if (paginationMode === 'shortened') {
    if (totalPages <= 7) {
        // Show all pages if total is small
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = (i === currentPage) ? 'active' : '';
            btn.addEventListener('click', () => {
                currentPage = i;
                renderList();
            });
            paginationContainer.appendChild(btn);
        }
    } else {
        // Show first, last, current ±1, with ellipsis as needed

        function createBtn(page) {
            const btn = document.createElement('button');
            btn.textContent = page;
            btn.className = (page === currentPage) ? 'active' : '';
            btn.addEventListener('click', () => {
                currentPage = page;
                renderList();
            });
            return btn;
        }

        // Always show first page
        paginationContainer.appendChild(createBtn(1));

        // If currentPage > 3, show ellipsis after 1
        if (currentPage > 3) {
            const ellipsis1 = document.createElement('span');
            ellipsis1.textContent = '...';
            ellipsis1.className = 'pagination-ellipsis';
            paginationContainer.appendChild(ellipsis1);
        }

        // Pages before current
        for (let i = currentPage - 1; i <= currentPage + 1; i++) {
            if (i > 1 && i < totalPages) {
                paginationContainer.appendChild(createBtn(i));
            }
        }

        // If currentPage < totalPages - 2, show ellipsis before last page
        if (currentPage < totalPages - 2) {
            const ellipsis2 = document.createElement('span');
            ellipsis2.textContent = '...';
            ellipsis2.className = 'pagination-ellipsis';
            paginationContainer.appendChild(ellipsis2);
        }

        // Always show last page
        paginationContainer.appendChild(createBtn(totalPages));
    }
}

        }

        // Search handler
        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            filteredItems = allItems.filter(li => li.textContent.toLowerCase().includes(term));
            currentPage = 1;
            renderList();
        });

        // Click handler for PDFs
        pdfList.addEventListener('click', function (e) {
            const li = e.target.closest('li');
            if (!li) return;
            const pdfUrl = li.getAttribute('data-url');
            if (pdfUrl && viewer) {
                viewer.src = pdfUrl + '#toolbar=0';
            }
        });

        renderList();
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/pdf_library_widget.default',
            function ($scope) {
                initPdfLibrary($scope);
            }
        );
    });
})(jQuery);
