const $ = require('jquery');
require('select2');

module.exports = {
    init: function (parent) {
        $.fn.select2.defaults.set('language', 'sk');
        parent.find('select[data-trexima-european-cv-bind-select2]').each(function () {
            var dependency = getDataByPrefix($(this), 'dependency');
            var ajaxUrl = $(this).data('ajax--url');
            var allowClear = $(this).data('allow-clear');
            var placeholder = $(this).data('placeholder');
            var maximumSelectionLength = $(this).data('data-maximum-selection-length');
            var ajaxOptions;
            if (ajaxUrl) {
                ajaxOptions = {
                    url: ajaxUrl,
                    dataType: 'json',
                    delay: 250, // wait 250 milliseconds before triggering the request
                    cache: true,
                    method: 'POST',
                    transport: function (params, success, failure) {
                        var request;
                        if (!params.cache) {
                            request = $.ajax(params);
                            request.then(success);
                            request.fail(failure);
                            return request;
                        }

                        var cacheKey = JSON.stringify(params);
                        if (typeof __cache[cacheKey] !== 'undefined') {
                            let result = __cache[cacheKey];
                            success(result);
                            return {};
                        }

                        request = $.ajax(params);
                        request.then(function (data) {
                            __cache[cacheKey] = data;
                            return data;
                        })

                        request.then(success);
                        request.fail(failure);
                        return request;
                    },
                    data: function (params) {
                        let dependencyData = {};
                        for (var key in dependency) {
                            dependencyData[key] = $(dependency[key]).val();
                        }

                        // Query parameters will be ?search=[term]
                        return {
                            term: params.term,
                            page: params.page || 1,
                            data: dependencyData
                        }
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * data.perPage) < data.total
                            }
                        };
                    }
                };
            }

            // Validate Select2 on change if Parsley is loaded
            if (typeof $.fn.parsley !== 'undefined') {
                $('select').on('select2:select', function (e) {
                    $(this).parsley().validate();
                });
            }

            $(this).select2({
                ajax: ajaxOptions,
                allowClear: allowClear,
                placeholder: placeholder,
                maximumSelectionLength: maximumSelectionLength,
                templateResult: function(result) {
                    // Hadnle ajax result with description
                    if (typeof result.description !== 'undefined') {
                        return $('<div />').text(result.text).append($('<div class="select2-description" />').text(result.description));
                    }

                    // Description as data attribute
                    if (result.element && $(result.element).data('select2-description')) {
                        return $('<div />').text(result.text).append($('<div class="select2-description" />').text($(result.element).data('select2-description')));
                    }

                    return result.text;
                },
                templateSelection: function(result) {
                    return result.text;
                },
                width: '100%',
                theme: "bootstrap",
                tags: true
            });
        });

        function getDataByPrefix(element, prefix) {
            var data = element.data();
            var dataWithPrefix = {};
            for (var key in data) {
                // Get only data with prefix and ignore data without suffix
                if (key.lastIndexOf(prefix, 0) !== 0 || key.length <= prefix.length) {
                    continue;
                }

                let suffix = key.substr(prefix.length);
                suffix = suffix.charAt(0).toLowerCase() + suffix.slice(1);
                dataWithPrefix[suffix] = data[key];
            }

            return dataWithPrefix;
        }
    }
};