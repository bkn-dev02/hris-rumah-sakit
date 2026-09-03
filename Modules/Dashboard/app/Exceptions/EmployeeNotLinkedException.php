<?php

namespace Modules\Dashboard\Exceptions;

use Exception;
use Illuminate\Http\Request;

class EmployeeNotLinkedException extends Exception
{
    protected string $title;

    public function __construct(
        string $message = 'Akun Anda belum terhubung dengan data karyawan.',
        string $title = 'Akun Belum Terhubung'
    ) {
        parent::__construct($message);
        $this->title = $title;
    }

    public function render(Request $request)
    {
        return response()
            ->view('dashboard::errors.employee-not-linked', [
                'message' => $this->getMessage(),
                'title'   => $this->title,
            ], 403);
    }
}
