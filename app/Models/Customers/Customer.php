<?php

namespace App\Models\Customers;

use App\Customers\Models\CustomerGuarantor;
use App\Models\Customers\CustomerCard;
use App\Models\Loans\Loan;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'terms_and_condition'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','transaction_pin',
    ];



    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getFullNameAttribute(){
        return $this->first_name . ' ' .$this->last_name;
    }


    public function device()
    {
        return $this->hasMany(CustomerDevice::class)->latest();
    }

    public function cardRequest()
    {
        return $this->hasMany(CardRequest::class)->latest();
    }

    public function bioData()
    {
        return $this->hasMany(CustomerBioData::class)->latest();
    }

    public function nextOfKin()
    {
        return $this->hasMany(CustomerNextOfKin::class)->latest();
    }

    public function identityCard()
    {
        return $this->hasMany(CustomerIdentityCard::class)->latest();
    }

    public function utility()
    {
        return $this->hasMany(CustomerUtility::class)->latest();
    }

    public function guarantor()
    {
        return $this->hasMany(CustomerGuarantor::class)->select(['name','phone','address'])->latest();
    }

    public function card(){
        return $this->hasMany(CustomerCard::class);
    }

    public function defaultCard(){
        return $this->hasMany(CustomerCard::class)->where('default',true);
    }

    public function loan(){
        return $this->hasMany(Loan::class)->latest();
    }

    public function transactions(){
        return $this->hasMany(Transaction::class)->latest();
    }

    public function referred($code){
        return $this->where('referred_by',$code)->get();
    }
}
