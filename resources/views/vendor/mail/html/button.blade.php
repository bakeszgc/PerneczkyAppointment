@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])

<style>
    .button{
        background-color: rgb(0 24 213);
        color: white;
        border-width: 1px;
        border-radius: 0.375rem;
        font-weight: 700;
        padding: 0.5rem;
        max-height: fit-content;
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    .button:hover{
        background-color: rgb(15 15 15);
        filter: drop-shadow(0 4px 3px rgb(0 0 0 / 0.07));
    }
</style>

<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="{{ $align }}">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="{{ $align }}">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td>
                                    <a href="{{ $url }}" class="button" target="_blank" rel="noopener">{{ $slot }}</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
