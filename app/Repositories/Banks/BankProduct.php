<?php

namespace Whatsloan\Repositories\Banks;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Attachments\Attachable;
use Whatsloan\Repositories\Addresses\Addressable;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Services\Audits\Auditable;

class BankProduct extends Model
{
    use Attachable,
    Auditable,
    Addressable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'bank_id', 'product_id'];

    /**
     * Bank project has a bank
     * @return type
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Bank product has a bank
     * @return type
     */
    public function product()
    {
        return $this->belongsTo(Type::class);
    }

        /**
     * Returns the path of documents
     *
     * @return string
     */
    public function getProductPath()
    {
        return 'products/' . $this->uuid . '/';
    }

         /**
     * Store a new bank product document
     *
     * @param $id
     * @param array $request
     * @return mixed
     */
    public function storeBankProductDocument($id, array $request)
    {
        $bank_product = static::find($id);
        $type = Type::where('id',$bank_product->product_id)->first();
        $attachment = new Attachment([
            'uuid' => uuid(),
            'name' => $request['name'],
            'description' => strip_tags($request['description']),
            'type' => $type->key,
            'uri' => $request['uri'],
        ]);

        return $bank_product->attachments()->save($attachment);
    }

    /**
     * Update a bank Product document
     *
     * @param $id
     * @param $documentId
     * @param array $request
     * @return mixed
     */
    public function updateBankProductDocument($id, $documentId, array $request)
    {
        $bank_product = static::with(['attachments' => function($query) use ($documentId) {
                        $query->whereId($documentId)->take(1);
                    }])->where('id', $id)->first();
        $type = Type::where('id',$bank_product->product_id)->first();

        return $bank_product->attachments->first()->update([
                            'name' => $request['name'],
                            'description' => strip_tags($request['description']),
                            'type' => $type->key,
                            ]);
    }


}
