<?php

/**
 * @OA\Schema(
 *      schema="ResponseErrorDefault",
 *      @OA\Property(property="status", type="string", example="error"),
 *      @OA\Property(property="errorCode", type="integer", example="0"),
 *      @OA\Property(property="errorMessage", type="string", example=""),
 *      @OA\Property(property="errorData", type="array", example="{}", @OA\Items(
 *      )),
 * )
 */