<?php

namespace Jokoli\Payment\Http\Controllers;

use Carbon\CarbonPeriod;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Jokoli\Payment\Events\PaymentWasSuccessful;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jokoli\Payment\Enums\PaymentStatus;
use Jokoli\Payment\Gateways\Gateway;
use Jokoli\Payment\Models\Payment;
use Jokoli\Payment\Repository\PaymentRepository;
use PDO;

class PaymentController extends Controller
{
    private PaymentRepository $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('manage', Payment::class);
        $payments = $this->paymentRepository->paginate($request);
        $last30DaysSales = $this->paymentRepository->getLastNDaysSales(30);
        $allSales = $this->paymentRepository->getAllSales();
        $last30Dates = CarbonPeriod::create(Carbon::yesterday()->subDays(30), Carbon::yesterday());
        $last30DaysTransactions = $this->paymentRepository->last30DaysTransactions(30);
        return view('Payment::index', compact('payments', 'last30DaysSales', 'allSales', 'last30Dates', 'last30DaysTransactions'));
    }

    public function purchases()
    {
        $payments = $this->paymentRepository->getUserPayments();
        return view('Payment::purchases',compact('payments'));
    }

    public function callback(Request $request)
    {
        $payment = $this->paymentRepository->findByInvoiceId($request->Authority);
        if (!$payment) {
            errorFeedback("تراکنش ناموفق", "تراکنش مورد نظر یافت نشد");
            return redirect('/');
        }
        $result = resolve(Gateway::class)->verify($payment, $request);
        if (is_array($result)) {
            errorFeedback("تراکنش ناموفق", $result['message']);
            $this->paymentRepository->changeStatus($payment, PaymentStatus::Fail);
        } else {
            showFeedback("عملیات موفق", "پرداخت با موفقیت انجام شد");
            $this->paymentRepository->changeStatus($payment, PaymentStatus::Success);
            event(new PaymentWasSuccessful($payment));
        }
        return redirect($payment->paymentable->path());
    }
}
