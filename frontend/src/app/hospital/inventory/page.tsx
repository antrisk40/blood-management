'use client';

import { useQuery } from '@tanstack/react-query';
import { apiClient } from '@/services/apiClient';
import { useState } from 'react';
import BloodSampleForm from '@/components/forms/BloodSampleForm';
import { Loader2, Plus, Trash2 } from 'lucide-react';

interface BloodSample {
  id: number;
  group_name: string;
  units_available: number;
  expiry_date: string;
}

export default function HospitalInventoryPage() {
  const [showAddForm, setShowAddForm] = useState(false);

  const { data, isLoading, refetch } = useQuery({
    queryKey: ['hospital-inventory'],
    queryFn: async () => {
      const response = await apiClient.get('/hospital/inventory');
      return response.data.data as BloodSample[];
    }
  });

  const handleDelete = async (id: number) => {
    if (confirm('Are you sure you want to delete this sample?')) {
      try {
        await apiClient.delete(`/blood-samples/${id}`);
        refetch();
      } catch (err) {
        alert('Failed to delete sample');
      }
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div className="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Blood Inventory</h1>
          <p className="mt-2 text-sm text-gray-500">Manage your hospital's blood stock and availability.</p>
        </div>
        <div className="mt-4 sm:mt-0">
          <button
            onClick={() => setShowAddForm(!showAddForm)}
            className="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700"
          >
            <Plus className="h-4 w-4 mr-2" />
            Add Blood Sample
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2">
          {isLoading ? (
            <div className="flex justify-center py-12">
              <Loader2 className="h-8 w-8 animate-spin text-red-600" />
            </div>
          ) : (
            <div className="bg-white shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg overflow-hidden">
              <table className="min-w-full divide-y divide-gray-300">
                <thead className="bg-gray-50">
                  <tr>
                    <th scope="col" className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Blood Group</th>
                    <th scope="col" className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Units Available</th>
                    <th scope="col" className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Expiry Date</th>
                    <th scope="col" className="relative py-3.5 pl-3 pr-4 sm:pr-6"><span className="sr-only">Actions</span></th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-200 bg-white">
                  {data?.length === 0 ? (
                    <tr>
                      <td colSpan={4} className="py-8 text-center text-sm text-gray-500">No inventory found. Add your first sample.</td>
                    </tr>
                  ) : (
                    data?.map((sample) => (
                      <tr key={sample.id}>
                        <td className="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                          <div className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {sample.group_name}
                          </div>
                        </td>
                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{sample.units_available}</td>
                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          {new Date(sample.expiry_date).toLocaleDateString()}
                        </td>
                        <td className="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                          <button
                            onClick={() => handleDelete(sample.id)}
                            className="text-red-600 hover:text-red-900"
                          >
                            <Trash2 className="h-4 w-4" />
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          )}
        </div>

        <div className="lg:col-span-1">
          {showAddForm ? (
            <BloodSampleForm 
              onSuccess={() => { setShowAddForm(false); refetch(); }} 
              onCancel={() => setShowAddForm(false)} 
            />
          ) : (
            <div className="bg-gray-50 rounded-lg border border-gray-200 border-dashed p-8 text-center">
              <p className="text-sm text-gray-500">Click "Add Blood Sample" to update your inventory.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
