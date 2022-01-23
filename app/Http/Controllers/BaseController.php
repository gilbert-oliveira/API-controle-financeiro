<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


abstract class BaseController extends Controller
{
    protected string $class;
    protected string $resorceName;

    public function index(): JsonResponse
    {
        return response()->json($this->class::all());
    }

    public function store(Request $request): JsonResponse
    {
        $this->validateResource($request);

        // Cadastra o recurso.
        $revenue = $this->class::create($request->all());

        // Retorna o recurso cadatrado com o código de criado (201).
        return response()->json($revenue, 201);
    }

    public function show($id): JsonResponse
    {
        $revenue = $this->class::find($id);

        if (is_null($revenue)) {
            return response()->json('', 204);
        }

        return response()->json($revenue);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $revenue = $this->class::find($id);

        if (is_null($revenue)) {
            return response()->json([
                'error' => ucfirst($this->resorceName) . ' não encontrada!'
            ], 404);
        }

        $this->validateResource($request, $revenue->id);

        $revenue->fill($request->all());
        $revenue->save();

        return response()->json($revenue);
    }

    public function destroy($id): JsonResponse
    {
        $qtdRemoved = $this->class::destroy($id);

        if ($qtdRemoved === 0) {
            return response()->json([
                'error' => ucfirst($this->resorceName) . ' não encontrada!'
            ], 404);
        }

        return response()->json([
            'success' => ucfirst($this->resorceName) . ' removida com sucesso!'
        ]);
    }

    /**
     * Método para validações da requisição post.
     * @param Request $request Requisição do post.
     * @param int|null $id Id opcional para ignorar validações de valores unicos.
     * @return void
     */
    protected function validateResource(Request $request, int $id = null)
    {
        $this->validate($request, [
            'description' => [
                'required',
                Rule::unique('revenues')->where(function ($query) {
                    return $query->whereBetween('date', $this->getDateBeteween(\request()->date));
                })->ignore($id)
            ],
            'value' => [
                'required',
            ],
            'date' => [
                'required',
                'date'
            ],
        ], [
            'description.required' => "Por favor. Informe a descrição da $this->resorceName!",
            'description.unique' => 'Descrição já cadatrada para o mês informado!',

            'value.required' => "Por favor. Informe o valor da $this->resorceName.",

            'date.required' => "Por favor. Informe a data da $this->resorceName.",
            'date.date' => "Por favor. Informe uma data no formato 2022-12-30"
        ]);
    }

    /**
     * Método para extrair o primeiro e último dia do mês.
     * @param string $date Data a ser extraido o primeiro e último dia do mês.
     * @return array  Array com a primeira e última data do mês.
     */
    private function getDateBeteween(string $date): array
    {
        return [
            // Recupera a data com o primeiro dia do mês.
            date("Y-m-01", strtotime($date)),

            // Recupera a data com o último dia do mês.
            date("Y-m-t", strtotime($date))
        ];
    }
}
